/* =========================================================
   Talay Trang — Booking page (Wizard flow v3)
   - 4-step wizard: boat → program → trip → contact
   - Coupon-style pick cards
   - Real-time price calculation
   - Auto-advance on selection
   - Per-step validation
   - URL pre-fill (?boat= , ?program= , ?people= , ?date=)
   ========================================================= */

(() => {
  const ready = (fn) => document.readyState !== 'loading'
    ? fn() : document.addEventListener('DOMContentLoaded', fn);

  ready(() => {
    const form = document.getElementById('booking-form');
    if (!form) return;

    const { SITE, BOATS, OPTIONS, PROGRAMS, ICONS } = window.TT;
    const fmt = new Intl.NumberFormat('th-TH');
    const SHUTTLE_PRICE = 1000;

    const STEPS = ['boat', 'program', 'trip', 'contact'];
    let currentStep = 'boat';
    let autoAdvanceTimer = null;

    /* ---------- populate inline icons ---------- */
    document.querySelectorAll('[data-icon-name]').forEach(el => {
      const name = el.dataset.iconName;
      if (ICONS[name]) el.innerHTML = ICONS[name];
    });

    /* =========================================================
       Pick card templates — coupon style
       ========================================================= */
    const checkSvg = ICONS.check ||
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13l4 4L19 7"/></svg>';

    const boatChoices = document.getElementById('boat-choices');
    const programChoices = document.getElementById('program-choices');
    const optionChoices = document.getElementById('option-choices');

    boatChoices.innerHTML = BOATS.map(b => `
      <label class="pick" data-pick="boat" data-id="${b.id}">
        <input type="radio" name="boat" value="${b.id}" data-price="${b.basePrice}">
        <div class="pick-card">
          <div class="pick-top">
            <div class="pick-thumb">
              <img src="${b.image}" alt="${b.name}" loading="lazy"/>
            </div>
            <div class="pick-body">
              <span class="pick-tag">${b.tag}</span>
              <strong class="pick-title">${b.name}</strong>
              <span class="pick-meta">Capacity ${b.capacity}</span>
              <span class="pick-desc">${b.short}</span>
            </div>
          </div>
          <div class="pick-divider"></div>
          <div class="pick-bot">
            <div class="pick-price">
              <small>From</small>
              <strong>฿${fmt.format(b.basePrice)}</strong>
            </div>
            <span class="pick-cta">
              <span class="cta-default">Select</span>
              <span class="cta-checked">${checkSvg} Selected</span>
            </span>
          </div>
        </div>
      </label>
    `).join('');

    function renderPrograms(boatId) {
      const currentProgramId = form.querySelector('input[name="program"]:checked')?.value;
      const list = boatId ? PROGRAMS.filter(p => p.boats.includes(boatId)) : PROGRAMS;
      programChoices.innerHTML = list.map(p => `
        <label class="pick" data-pick="program" data-id="${p.id}">
          <input type="radio" name="program" value="${p.id}" data-price="${p.basePrice}">
          <div class="pick-card">
            <div class="pick-top">
              <div class="pick-thumb">
                <img src="${p.image}" alt="${p.name}" loading="lazy"/>
                ${p.ribbon ? `<span class="pick-ribbon">${p.ribbon}</span>` : ''}
              </div>
              <div class="pick-body">
                <span class="pick-tag">${p.duration}</span>
                <strong class="pick-title">${p.name}</strong>
                <span class="pick-route">${p.route}</span>
                <span class="pick-desc">${(p.desc || '').slice(0, 90)}…</span>
              </div>
            </div>
            <div class="pick-divider"></div>
            <div class="pick-bot">
              <div class="pick-price">
                <small>From</small>
                <strong>฿${fmt.format(p.basePrice)}</strong>
              </div>
              <span class="pick-cta">
                <span class="cta-default">Select</span>
                <span class="cta-checked">${checkSvg} Selected</span>
              </span>
            </div>
          </div>
        </label>
      `).join('');
      // Restore previous program selection if still in the filtered list
      if (currentProgramId) {
        const r = programChoices.querySelector(`input[name="program"][value="${currentProgramId}"]`);
        if (r) r.checked = true;
      }
      bindLiveCalc();
    }
    renderPrograms();

    optionChoices.innerHTML = OPTIONS.map(o => `
      <label class="addon">
        <input type="checkbox" name="option" value="${o.id}" data-price="${o.price}" data-unit="${o.unit}">
        <span class="addon-icon">${ICONS[o.icon] || ICONS.plus}</span>
        <span class="addon-body">
          <strong>${o.label}</strong>
          <span class="addon-desc">${o.desc}</span>
        </span>
        <span class="addon-price">${o.priceLabel}</span>
      </label>
    `).join('');

    /* =========================================================
       Quantity stepper
       ========================================================= */
    const adultsInput = form.querySelector('input[name="adults"]');
    const childrenInput = form.querySelector('input[name="children"]');
    form.querySelectorAll('.qty-stepper').forEach(stepper => {
      stepper.addEventListener('click', (e) => {
        const btn = e.target.closest('.qty-btn');
        if (!btn) return;
        const input = stepper.querySelector('.qty-input');
        const dir = btn.dataset.qty === '+' ? 1 : -1;
        const min = Number(input.min) || 1;
        const max = Number(input.max) || 80;
        const cur = Number(input.value) || min;
        const next = Math.max(min, Math.min(max, cur + dir));
        if (next !== cur) {
          input.value = next;
          input.dispatchEvent(new Event('input', { bubbles: true }));
        }
      });
    });

    /* =========================================================
       State / calculation
       ========================================================= */
    function getState() {
      const fd = new FormData(form);
      const adults = Number(fd.get('adults') || 0);
      const children = Number(fd.get('children') || 0);
      return {
        name:      (fd.get('name') || '').toString().trim(),
        phone:     (fd.get('phone') || '').toString().trim(),
        date:      (fd.get('date') || '').toString(),
        adults,
        children,
        people:    adults + children,
        transport: (fd.get('transport') || '').toString(),
        boatId:    (fd.get('boat') || '').toString(),
        programId: (fd.get('program') || '').toString(),
        optionIds: fd.getAll('option').map(String),
        note:      (fd.get('note') || '').toString().trim(),
      };
    }

    function childRateFor(program) {
      if (!program) return 0;
      if (program.childPrice != null && program.childPrice !== '') {
        return Number(program.childPrice);
      }
      return Math.round(Number(program.basePrice) * 0.74);
    }

    function calculate(state) {
      const boat = BOATS.find(b => b.id === state.boatId);
      const program = PROGRAMS.find(p => p.id === state.programId);
      const opts = OPTIONS.filter(o => state.optionIds.includes(o.id));
      const adults = Math.max(state.adults || 0, 0);
      const children = Math.max(state.children || 0, 0);
      const people = adults + children;

      let tripBase = 0;
      let ticketLines = [];
      if (program) {
        const adultPrice = Number(program.basePrice) || 0;
        const childPrice = childRateFor(program);
        const adultTotal = adults * adultPrice;
        const childTotal = children * childPrice;
        tripBase = adultTotal + childTotal;
        if (adults > 0) {
          ticketLines.push({
            label: 'Adult',
            desc: `฿${fmt.format(adultPrice)} × ${adults} guests`,
            value: adultTotal,
          });
        }
        if (children > 0) {
          ticketLines.push({
            label: 'Child',
            desc: `฿${fmt.format(childPrice)} × ${children} guests`,
            value: childTotal,
          });
        }
      } else if (boat) {
        tripBase = boat.basePrice;
      }

      const wantShuttleFromTransport = state.transport === 'Hotel pickup' || state.transport === 'ให้รถไปรับ' /* legacy Thai value */;
      const hasShuttleOpt = state.optionIds.includes('shuttle');
      const shuttle = (wantShuttleFromTransport && !hasShuttleOpt) ? SHUTTLE_PRICE : 0;

      let addonsTotal = 0;
      const addonLines = [];
      opts.forEach(o => {
        if (o.id === 'shuttle' && wantShuttleFromTransport) return;
        const sub = o.unit === 'per_person' ? o.price * people : o.price;
        addonsTotal += sub;
        addonLines.push({
          id: o.id,
          label: o.label,
          desc: o.unit === 'per_person'
            ? `฿${fmt.format(o.price)} × ${people || 0} guests`
            : 'Flat rate / trip',
          value: sub,
        });
      });

      const total = tripBase + shuttle + addonsTotal;
      const perHead = people > 0 ? Math.round(total / people) : 0;

      return { boat, program, opts, adults, children, people, tripBase, ticketLines, shuttle, addonsTotal, addonLines, total, perHead };
    }

    /* =========================================================
       Cart summary render
       ========================================================= */
    const cartHero      = document.getElementById('cart-hero');
    const cartHeroImg   = document.getElementById('cart-hero-img');
    const cartHeroName  = document.getElementById('cart-hero-name');
    const cartHeroRoute = document.getElementById('cart-hero-route');
    const cartItems     = document.getElementById('cart-items');
    const cartSubtotal  = document.getElementById('cart-subtotal');
    const subBase       = document.getElementById('sub-base');
    const subShuttle    = document.getElementById('sub-shuttle');
    const rowShuttle    = document.getElementById('row-shuttle');
    const subPeople     = document.getElementById('sub-people');
    const subPerhead    = document.getElementById('sub-perhead');
    const rowPerhead    = document.getElementById('row-perhead');
    const totalAmount   = document.getElementById('total-amount');
    const cartCount     = document.getElementById('cart-count');

    function animateNumber(el, from, to, ms = 450) {
      const t0 = performance.now();
      const step = (now) => {
        const t = Math.min(1, (now - t0) / ms);
        const ease = 1 - Math.pow(1 - t, 3);
        const v = Math.round(from + (to - from) * ease);
        el.textContent = fmt.format(v);
        if (t < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    }

    let lastTotal = 0;
    function renderSummary() {
      const state = getState();
      const calc = calculate(state);

      if (calc.boat) {
        cartHero.hidden = false;
        cartHeroImg.src = calc.boat.image;
        cartHeroImg.alt = calc.boat.name;
        cartHeroName.textContent = calc.boat.name;
        cartHeroRoute.textContent = calc.program
          ? calc.program.name
          : 'No program selected';
      } else {
        cartHero.hidden = true;
      }

      const itemRows = [];

      if (calc.boat) {
        itemRows.push(`
          <div class="cart-line">
            <span class="line-icon">${ICONS.anchor}</span>
            <span class="line-body">
              <strong>${calc.boat.name}</strong>
              <small>${calc.boat.capacity}</small>
            </span>
            <span class="line-pill">Selected</span>
          </div>
        `);
      }
      if (calc.program) {
        itemRows.push(`
          <div class="cart-line">
            <span class="line-icon">${ICONS.route}</span>
            <span class="line-body">
              <strong>${calc.program.name}</strong>
              <small>${calc.program.duration}</small>
            </span>
            <span class="line-pill">Selected</span>
          </div>
        `);
      }
      if (calc.tripBase > 0) {
        if (calc.ticketLines.length) {
          calc.ticketLines.forEach(t => {
            itemRows.push(`
              <div class="cart-line cart-line-base">
                <span class="line-icon line-icon-strong">${ICONS.calculator || ICONS.anchor}</span>
                <span class="line-body">
                  <strong>${t.label}</strong>
                  <small>${t.desc}</small>
                </span>
                <span class="line-amt">฿${fmt.format(t.value)}</span>
              </div>
            `);
          });
        } else {
          itemRows.push(`
            <div class="cart-line cart-line-base">
              <span class="line-icon line-icon-strong">${ICONS.calculator || ICONS.anchor}</span>
              <span class="line-body">
                <strong>Private charter trip price</strong>
                <small>Boat + tour program</small>
              </span>
              <span class="line-amt">฿${fmt.format(calc.tripBase)}</span>
            </div>
          `);
        }
      }
      if (calc.shuttle > 0) {
        itemRows.push(`
          <div class="cart-line">
            <span class="line-icon">${ICONS.carPick}</span>
            <span class="line-body">
              <strong>Hotel shuttle</strong>
              <small>Flat rate / trip</small>
            </span>
            <span class="line-amt">฿${fmt.format(SHUTTLE_PRICE)}</span>
          </div>
        `);
      }
      calc.addonLines.forEach(a => {
        itemRows.push(`
          <div class="cart-line">
            <span class="line-icon">${ICONS.plus}</span>
            <span class="line-body">
              <strong>${a.label}</strong>
              <small>${a.desc}</small>
            </span>
            <span class="line-amt">฿${fmt.format(a.value)}</span>
          </div>
        `);
      });

      const totalItemCount = itemRows.length;
      cartCount.textContent = `${totalItemCount} items`;

      if (totalItemCount === 0) {
        cartItems.innerHTML = `
          <div class="cart-empty">
            <span class="cart-empty-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 3h2l2 12h12l2-9H6"/><circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/>
              </svg>
            </span>
            <p>Nothing selected yet<br/>Choose a boat and program to get started</p>
          </div>
        `;
        cartSubtotal.hidden = true;
      } else {
        cartItems.innerHTML = itemRows.join('');
        cartSubtotal.hidden = false;
        subBase.textContent = calc.people > 0
          ? `${calc.adults} adults${calc.children ? ` · ${calc.children} children` : ''}`
          : '— guests';
        rowShuttle.hidden = true;
        subPeople.textContent = state.date
          ? formatThaiDate(state.date)
          : 'Not selected';
        rowPerhead.hidden = calc.people <= 0 || calc.total <= 0;
        subPerhead.textContent = '฿' + fmt.format(calc.perHead);
      }

      animateNumber(totalAmount, lastTotal, calc.total, 400);
      lastTotal = calc.total;

      updatePips(state);

      return { state, calc };
    }

    /* =========================================================
       Wizard navigation
       ========================================================= */
    const steps = Array.from(form.querySelectorAll('.wizard-step'));
    const pips  = Array.from(document.querySelectorAll('.booking-progress .pip'));
    const pipLines = Array.from(document.querySelectorAll('.booking-progress .pip-line'));

    function gotoStep(targetKey, { skipScroll = false } = {}) {
      if (!STEPS.includes(targetKey)) return;
      clearTimeout(autoAdvanceTimer);
      currentStep = targetKey;

      steps.forEach(s => s.classList.toggle('is-active', s.dataset.step === targetKey));

      // Pips: mark previous steps as done, current as active
      const curIdx = STEPS.indexOf(targetKey);
      pips.forEach((li, i) => {
        const idx = STEPS.indexOf(li.dataset.pip);
        li.classList.toggle('is-active', idx === curIdx);
        li.classList.toggle('is-done', idx < curIdx);
      });
      pipLines.forEach(line => {
        const afterStep = line.dataset.lineAfter;
        const afterIdx = STEPS.indexOf(afterStep);
        line.classList.toggle('is-done', afterIdx < curIdx);
      });

      // Smooth scroll to top of booking section
      if (!skipScroll) {
        const sec = document.querySelector('.booking-section');
        if (sec) {
          const headerH = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--header-h')) || 70;
          const y = sec.getBoundingClientRect().top + window.scrollY - headerH - 20;
          window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
        }
      }
    }

    function validateStep(stepKey) {
      const state = getState();
      let ok = true;
      if (stepKey === 'boat') {
        if (!state.boatId) { setError('boat', 'Please select a boat first'); ok = false; }
        else setError('boat', '');
      } else if (stepKey === 'program') {
        if (!state.programId) { setError('program', 'Please select a program first'); ok = false; }
        else setError('program', '');
      } else if (stepKey === 'trip') {
        if (!state.date) { setError('date', 'Please select a travel date'); ok = false; }
        else setError('date', '');
        if (!state.adults || state.adults < 1) { setError('adults', 'Please enter at least 1 adult'); ok = false; }
        else setError('adults', '');
        if (state.children < 0) { setError('children', 'Invalid number of children'); ok = false; }
        else setError('children', '');
        if (!state.transport) { setError('transport', 'Please select a transport option'); ok = false; }
        else setError('transport', '');
      } else if (stepKey === 'contact') {
        if (!state.name) { setError('name', 'Please enter your name'); ok = false; }
        else setError('name', '');
        const phoneClean = state.phone.replace(/[^\d]/g, '');
        if (!state.phone || phoneClean.length < 9 || phoneClean.length > 12) {
          setError('phone', state.phone ? 'Invalid phone number' : 'Please enter your phone number');
          ok = false;
        } else setError('phone', '');
      }
      if (!ok) {
        const firstError = form.querySelector('.wizard-step.is-active .field.has-error');
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        window.TT.toast?.('Please complete all required fields');
      }
      return ok;
    }

    // Next / Back button handlers
    form.querySelectorAll('.wizard-next').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!validateStep(currentStep)) return;
        gotoStep(btn.dataset.next);
      });
    });
    form.querySelectorAll('.wizard-back').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        gotoStep(btn.dataset.back);
      });
    });

    /* Auto-advance after boat / program selection (small delay so user
       can see their selection animation, then move on) */
    function scheduleAutoAdvance(nextStep) {
      clearTimeout(autoAdvanceTimer);
      autoAdvanceTimer = setTimeout(() => {
        if (currentStep === 'boat' && nextStep === 'program') gotoStep('program');
        else if (currentStep === 'program' && nextStep === 'trip') gotoStep('trip');
      }, 550);
    }

    /* =========================================================
       Progress pips update based on state
       ========================================================= */
    function updatePips(state) {
      const done = {
        boat:    !!state.boatId,
        program: !!state.programId,
        trip:    !!state.date && state.people > 0 && !!state.transport,
        contact: !!state.name && state.phone.replace(/\D/g,'').length >= 9,
      };
      pips.forEach(li => {
        const k = li.dataset.pip;
        // Only mark as done if it's BEFORE the current step AND fully filled
        const idx = STEPS.indexOf(k);
        const curIdx = STEPS.indexOf(currentStep);
        if (idx < curIdx) {
          li.classList.add('is-done');
        } else if (idx === curIdx) {
          li.classList.add('is-active');
          li.classList.toggle('is-done', !!done[k]);
        } else {
          li.classList.remove('is-active');
          li.classList.toggle('is-done', !!done[k] && idx <= curIdx);
        }
      });
    }

    /* =========================================================
       Bind live calculation
       ========================================================= */
    function bindLiveCalc() {
      form.querySelectorAll('input, select, textarea').forEach(el => {
        el.removeEventListener('input', renderSummary);
        el.removeEventListener('change', onChange);
        el.addEventListener('input', renderSummary);
        el.addEventListener('change', onChange);
      });
    }
    function onChange(e) {
      if (e.target.name === 'boat') {
        renderPrograms(e.target.value);
        renderSummary();
        scheduleAutoAdvance('program');
      } else if (e.target.name === 'program') {
        renderSummary();
        scheduleAutoAdvance('trip');
      } else {
        renderSummary();
      }
    }
    bindLiveCalc();
    renderSummary();

    /* =========================================================
       Validation helpers
       ========================================================= */
    function setError(name, msg) {
      const field = form.querySelector(`[data-field="${name}"]`);
      if (!field) return;
      field.classList.toggle('has-error', !!msg);
      const errEl = field.querySelector('.field-error');
      if (errEl) errEl.textContent = msg || '';
    }

    function validateAll(state) {
      let ok = true;
      const phoneClean = state.phone.replace(/[^\d]/g, '');

      [['name','Please enter your name'], ['phone','Please enter your phone number'], ['date','Please select a travel date']]
        .forEach(([k, msg]) => {
          if (!state[k]) { setError(k, msg); ok = false; } else setError(k, '');
        });

      if (state.phone && (phoneClean.length < 9 || phoneClean.length > 12)) {
        setError('phone', 'Invalid phone number');
        ok = false;
      }
      if (!state.adults || state.adults < 1) {
        setError('adults', 'Please enter at least 1 adult');
        ok = false;
      } else setError('adults', '');
      if (state.children < 0) {
        setError('children', 'Invalid number of children');
        ok = false;
      } else setError('children', '');

      if (!state.transport) { setError('transport', 'Please select a transport option'); ok = false; }
      else setError('transport', '');

      if (!state.boatId) { setError('boat', 'Please select a boat type'); ok = false; }
      else setError('boat', '');

      if (!state.programId) { setError('program', 'Please select a program'); ok = false; }
      else setError('program', '');

      return ok;
    }

    /* =========================================================
       LINE message build
       ========================================================= */
    function buildLineMessage(state, calc) {
      const L = [];
      L.push('Hello, I would like to book a Trang sea boat trip');
      L.push('');
      L.push('— Booking contact —');
      L.push(`Name: ${state.name}`);
      L.push(`Phone: ${state.phone}`);
      L.push(`Travel date: ${formatThaiDate(state.date)}`);
      L.push(`Adults: ${state.adults} guests`);
      if (state.children > 0) L.push(`Children: ${state.children} guests`);
      L.push(`Total: ${state.people} guests`);
      L.push(`Transport: ${state.transport}`);
      L.push('');
      L.push('— Trip details —');
      L.push(`Boat type: ${calc.boat ? calc.boat.name : '-'}`);
      L.push(`Program: ${calc.program ? calc.program.name : '-'}`);
      if (calc.program) L.push(`Route: ${calc.program.route}`);
      L.push('');
      L.push('— Extra options —');
      if (calc.shuttle > 0) L.push(`• Shuttle (+฿${fmt.format(SHUTTLE_PRICE)})`);
      if (calc.addonLines.length === 0 && calc.shuttle === 0) {
        L.push('• None');
      } else {
        calc.addonLines.forEach(a => {
          L.push(`• ${a.label} (+฿${fmt.format(a.value)})`);
        });
      }
      L.push('');
      L.push('— Price summary —');
      if (calc.ticketLines.length) {
        calc.ticketLines.forEach(t => L.push(`${t.label}: ${t.desc} = ฿${fmt.format(t.value)}`));
      } else {
        L.push(`Trip price: ฿${fmt.format(calc.tripBase)}`);
      }
      if (calc.shuttle > 0) L.push(`Shuttle: +฿${fmt.format(calc.shuttle)}`);
      if (calc.addonsTotal > 0) L.push(`Extra options: +฿${fmt.format(calc.addonsTotal)}`);
      L.push(`>>> Estimated total: ฿${fmt.format(calc.total)} <<<`);
      if (calc.perHead > 0) L.push(`Average ฿${fmt.format(calc.perHead)} / person`);
      L.push('');
      if (state.note) {
        L.push('— Notes —');
        L.push(state.note);
        L.push('');
      }
      L.push('Please have an admin contact me to confirm the booking');
      return L.join('\n');
    }

    function formatThaiDate(d) {
      if (!d) return '-';
      try {
        const dt = new Date(d);
        const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return `${dt.getDate()} ${months[dt.getMonth()]} ${dt.getFullYear()}`;
      } catch { return d; }
    }

    /* =========================================================
       Submit handlers (final step + copy)
       ========================================================= */
    const submitBtn = document.getElementById('submit-line');
    const copyBtn = document.getElementById('copy-summary');

    submitBtn.addEventListener('click', (e) => {
      e.preventDefault();
      const { state, calc } = renderSummary();
      if (!validateAll(state)) {
        window.TT.toast?.('Please complete all required fields');
        // jump to the step containing the first error
        const firstError = form.querySelector('.field.has-error');
        if (firstError) {
          const owner = firstError.closest('.wizard-step');
          if (owner) gotoStep(owner.dataset.step);
          setTimeout(() => firstError.scrollIntoView({ behavior: 'smooth', block: 'center' }), 250);
        }
        return;
      }
      const msg = buildLineMessage(state, calc);
      try { if (navigator.clipboard) navigator.clipboard.writeText(msg); } catch {}
      window.TT.toast?.('Message copied — opening LINE…');
      window.open(SITE.lineUrl, '_blank', 'noopener');
    });

    copyBtn?.addEventListener('click', (e) => {
      e.preventDefault();
      const { state, calc } = renderSummary();
      if (!validateAll(state)) {
        window.TT.toast?.('Please complete all fields before copying');
        return;
      }
      const msg = buildLineMessage(state, calc);
      navigator.clipboard?.writeText(msg).then(
        () => window.TT.toast?.('Copied — ready to send on LINE'),
        () => window.TT.toast?.('Copy failed')
      );
    });

    /* =========================================================
       URL query pre-fill
       ========================================================= */
    const qp = new URLSearchParams(location.search);
    const qBoat   = qp.get('boat');
    const qProg   = qp.get('program');
    const qPeople = qp.get('people');
    const qDate   = qp.get('date');

    if (qPeople && !isNaN(+qPeople)) {
      adultsInput.value = Math.max(1, Math.min(80, +qPeople));
    }
    if (qDate) {
      const d = form.querySelector('input[name="date"]');
      if (d) d.value = qDate;
    }

    let initialStep = 'boat';
    if (qBoat) {
      const r = form.querySelector(`input[name="boat"][value="${qBoat}"]`);
      if (r) { r.checked = true; renderPrograms(qBoat); }
      initialStep = 'program';
    }
    if (qProg) {
      // Pre-select program in the (possibly filtered) program list
      requestAnimationFrame(() => {
        const r = form.querySelector(`input[name="program"][value="${qProg}"]`);
        if (r) r.checked = true;
        renderSummary();
      });
      // If both boat & program already chosen, jump to trip details.
      // Otherwise start at boat (step 1) so user picks a compatible boat.
      initialStep = qBoat ? 'trip' : 'boat';
    }

    const qStep = qp.get('step');
    if (qStep && STEPS.includes(qStep)) {
      initialStep = qStep;
    }

    // Render summary & move to initial step (without scrolling on first load)
    renderSummary();
    if (qp.get('tt_preview') === '1') {
      const previewStep = qp.get('step') || 'trip';
      if (STEPS.includes(previewStep)) {
        setTimeout(() => {
          gotoStep(previewStep, { skipScroll: true });
          document.dispatchEvent(new CustomEvent('tt:preview-ready', { detail: { step: previewStep } }));
        }, 50);
      }
    } else if (initialStep !== 'boat') {
      setTimeout(() => gotoStep(initialStep), 50);
    } else {
      updatePips(getState());
    }

    /* Set min date = today */
    const dateInput = form.querySelector('input[name="date"]');
    if (dateInput) {
      const today = new Date();
      const iso = today.toISOString().split('T')[0];
      dateInput.min = iso;
    }

    /* Rebind reveal animations for dynamically rendered content */
    window.TT.bindReveal?.(document);
  });
})();
