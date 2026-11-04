const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/program.html';
let t = fs.readFileSync(path, 'utf8');

function replaceOnce(from, to, label) {
  const fromCrlf = from.replace(/\n/g, '\r\n');
  if (t.includes(fromCrlf)) {
    t = t.replace(fromCrlf, to.replace(/\n/g, '\r\n'));
    console.log('OK', label);
    return true;
  }
  if (t.includes(from)) {
    t = t.replace(from, to);
    console.log('OK(lf)', label);
    return true;
  }
  console.log('MISS', label);
  return false;
}

replaceOnce(
  `        const rules = [
          [/เรือทัวร์|เรือใหญ่/, 'bigBoat'],
          [/เรือหางยาว|เรือกลับฝั่ง/, 'longtail'],
          [/เรือสปีด|speedboat/i, 'speedboat'],
          [/เรือ/, 'longtail'],
          [/อาหาร|บุฟเฟ|กาแฟ|ของว่าง|น้ำดื่ม|น้ำแข็ง|มื้อเช้า|มื้อเที่ยง/, 'cutlery'],
          [/ห้องพัก|ที่พัก|โรงแรม|พัก/, 'bed'],
          [/รถ|รับส่ง|รับลูกค้า/, 'car'],
          [/ไกด์|สตาฟ|มัคคุเทศ|พนักงาน/, 'users'],
          [/ดำน้ำ|หน้ากาก|เสื้อชู|อุปกรณ์/, 'scuba'],
          [/อุทยาน|ธรรมเนียม/, 'ticket'],
          [/ประกัน/, 'shield'],
        ];`,
  `        const rules = [
          [/tour boat|big boat|large boat/i, 'bigBoat'],
          [/long-?tail|return boat|shore boat/i, 'longtail'],
          [/speed\\s*boat|speedboat/i, 'speedboat'],
          [/\\bboat\\b/i, 'longtail'],
          [/lunch|breakfast|buffet|coffee|snack|drinking water|ice|meal/i, 'cutlery'],
          [/room|hotel|accommodation|stay|resort/i, 'bed'],
          [/transfer|pickup|pick-up|van|car|shuttle/i, 'car'],
          [/guide|staff|crew/i, 'users'],
          [/snorkel|diving|mask|life jacket|gear/i, 'scuba'],
          [/park fee|national park|admission/i, 'ticket'],
          [/insurance/i, 'shield'],
        ];`,
  'rules'
);

replaceOnce(
  `      const STOP_DETAILS = {
        'เกาะมุก': 'Arrive at Koh Muk, prepare swimming gear, and head to the first stop',
        'ถ้ำมรกต': 'Swim through the long cave to the inner beach — Unseen Thailand views and photos',
        'เกาะกระดาน': 'Relax on white sand, swim in clear water, and enjoy lunch on the boat',
        'เกาะเชือก': 'Snorkel colorful coral, fish schools, and underwater photo spots',
        'เกาะแหวน': 'Stop to snorkel coral and fish around Koh Waen',
        'รับลูกค้า': 'Pickup at meeting points in Trang city (surcharge may apply)',
        'ท่าเรือปากเมง': 'Meet at Pak Meng Pier, Sikao District, before departure',
        'กลับท่าเรือ': 'Return to Pak Meng Pier, rinse and change, and end the program',
      };`,
  `      const STOP_DETAILS = {
        'Koh Muk': 'Arrive at Koh Muk, prepare swimming gear, and head to the first stop',
        'Koh Mook': 'Arrive at Koh Muk, prepare swimming gear, and head to the first stop',
        'Emerald Cave': 'Swim through the long cave to the inner beach — Unseen Thailand views and photos',
        'Koh Kradan': 'Relax on white sand, swim in clear water, and enjoy lunch on the boat',
        'Koh Chueak': 'Snorkel colorful coral, fish schools, and underwater photo spots',
        'Koh Waen': 'Stop to snorkel coral and fish around Koh Waen',
        'Pick-up in Trang city': 'Pickup at meeting points in Trang city (surcharge may apply)',
        'Pak Meng Pier': 'Meet at Pak Meng Pier, Sikao District, before departure',
        'Return to pier': 'Return to Pak Meng Pier, rinse and change, and end the program',
      };`,
  'STOP_DETAILS'
);

replaceOnce(
  `      function parseItineraryDay(title) {
        const m = (title || '').match(/วันที่\\s*(\\d+)/);
        return m ? parseInt(m[1], 10) : null;
      }
      function stripDayPrefix(title) {
        const stripped = (title || '').replace(/^วันที่\\s*\\d+\\s*[·•\\-–—]\\s*/, '').trim();
        return stripped || title;
      }`,
  `      function parseItineraryDay(title) {
        const m = (title || '').match(/(?:Day|วันที่)\\s*(\\d+)/i);
        return m ? parseInt(m[1], 10) : null;
      }
      function stripDayPrefix(title) {
        const stripped = (title || '').replace(/^(?:Day|วันที่)\\s*\\d+\\s*[·•\\-–—]\\s*/i, '').trim();
        return stripped || title;
      }`,
  'parseItineraryDay'
);

replaceOnce(
  `        const isDay1Only = (t) => /วันที่\\s*1|ตุ๊กตุ๊กนำเที่ยววันที่\\s*1|รับจากสนามบิน|รถตุ๊กตุ๊กนำเที่ยว/.test(t);
        const isDay3Only = (t) => /วันที่\\s*3|city tour\\s*วันที่\\s*3|city tour/i.test(t) && /วันที่\\s*3|city tour/i.test(t);
        const isBreakfast = (t) => /อาหารเช้า/.test(t);
        const isReturn = (t) => /กลับฝั่ง|กลับท่า|เรือกลับ|เรือหางยาวกลับ/.test(t);
        const isHotel = (t) => /ห้องพัก|ที่พัก|ค่าที่พัก/.test(t);
        const isSeaDay = (t) => /เรือ|อาหารเที่ยง|อาหารกลาง|บุฟเฟ่ต์|ดำน้ำ|อุปกรณ์|ไกด์|สตาฟ|มัคคุเทศ|อุทยาน|ประกัน|กาแฟ|ว่าง|น้ำดื่ม|น้ำแข็ง|รับลูกค้า|รถตู้รับส่งวันเที่ยว|รถรับส่งในเมือง/.test(t);`,
  `        const isDay1Only = (t) => /(?:Day|วันที่)\\s*1|tuk-?tuk.*(?:Day|วันที่)\\s*1|airport pickup|airport transfer|tuk-?tuk tour/i.test(t);
        const isDay3Only = (t) => /(?:Day|วันที่)\\s*3|city tour/i.test(t);
        const isBreakfast = (t) => /breakfast|อาหารเช้า/i.test(t);
        const isReturn = (t) => /return to (shore|pier|mainland)|return boat|long-?tail.*return|กลับฝั่ง|กลับท่า|เรือกลับ/i.test(t);
        const isHotel = (t) => /room|hotel|accommodation|lodging|stay|ห้องพัก|ที่พัก|ค่าที่พัก/i.test(t);
        const isSeaDay = (t) => /boat|lunch|buffet|snorkel|diving|gear|guide|staff|park fee|insurance|coffee|snack|drinking water|ice|pickup|pick-up|van transfer|city transfer|เรือ|อาหารเที่ยง|บุฟเฟ่ต์|ดำน้ำ|ไกด์|อุทยาน|ประกัน/i.test(t);`,
  'day helpers'
);

replaceOnce(
  `            if (/รถตู้\\s*city tour/i.test(text)) { put(3, text); continue; }`,
  `            if (/van\\s*city tour|รถตู้\\s*city tour/i.test(text)) { put(3, text); continue; }`,
  'van city tour'
);

replaceOnce(
  `            if (/ห้องพัก\\s*2\\s*คืน|ที่พัก\\s*2\\s*คืน|ค่าที่พัก\\s*2/.test(t)) {
              put(1, text.replace(/\\s*2\\s*คืน/, ' · check-in'));
              put(2, 'Continue stay at accommodation');
              continue;
            }
            if (/เกาะไหง\\s*2\\s*คืน/.test(t)) {
              put(1, text);
              put(2, 'Relax on Koh Hai');
              continue;
            }
            if (isHotel(text)) { put(1, text); continue; }
            if (/รถตู้รับส่ง/.test(t) && !/city tour/i.test(t)) { put(2, text); continue; }
            if (isSeaDay(t) || /เกาะรอก|สปีดโบ/.test(t)) { put(2, text); continue; }`,
  `            if (/(?:room|hotel|accommodation|lodging).{0,20}2\\s*nights?|ห้องพัก\\s*2\\s*คืน|ที่พัก\\s*2\\s*คืน|ค่าที่พัก\\s*2/i.test(t)) {
              put(1, text.replace(/\\s*2\\s*(?:nights?|คืน)/i, ' · check-in'));
              put(2, 'Continue stay at accommodation');
              continue;
            }
            if (/koh\\s*(?:hai|ngai).{0,12}2\\s*nights?|เกาะไหง\\s*2\\s*คืน/i.test(t)) {
              put(1, text);
              put(2, 'Relax on Koh Ngai');
              continue;
            }
            if (isHotel(text)) { put(1, text); continue; }
            if (/van transfer|shuttle van|รถตู้รับส่ง/i.test(t) && !/city tour/i.test(t)) { put(2, text); continue; }
            if (isSeaDay(t) || /koh\\s*rok|speed\\s*boat|เกาะรอก|สปีดโบ/i.test(t)) { put(2, text); continue; }`,
  '3d2n branching'
);

const enc = (s) => encodeURI(s);
replaceOnce(
  `      const HOTEL_IMAGE_KEYS = [
        ['ชมตรัง', \`\${WW_HOTEL}/2019/08/ชมตรัง-min.jpg\`],
        ['เมซอง', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4095006_190814_0048.jpg\`],
        ['ทับเที่ยง', \`\${WW_HOTEL}/2019/08/ณ-ทับเที่ยง-min.jpg\`],
        ['อิโค่อิ', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4095029_190814_0002-min-1024x683.jpg\`],
        ['ไมตรี', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4095010_190814_0055-min-1024x683.jpg\`],
        ['ธรรมรินทร์', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4094997_190814_0071-min-1024x683.jpg\`],
        ['เรือรัษฎา', \`\${WW_HOTEL}/2019/08/Superior_sm-min-1024x683.jpg\`],
        ['แคมปิ้ง', \`\${WW_HOTEL}/2025/08/เกาะไหง-รีสอร์ท-1-Nov-23-Dec-23-11-Jan-24-Apr-24-3.jpg\`],
        ['เกาะไหง รีสอร์ท', \`\${WW_HOTEL}/2025/08/เกาะไหง-รีสอร์ท-1-Nov-23-Dec-23-11-Jan-24-Apr-24.jpg\`],
        ['แฟนตาซี', \`\${WW_HOTEL}/2025/08/แฟนตาซี-รีสอร์ท-1-May-31-Oct-23-2.jpg\`],
        ['ทับวารินทร์', \`\${WW_HOTEL}/2025/08/P2-นอนเกาะไหง-โปรแกรมทัวร์-ทับวารินทร์.jpg\`],
        ['ลิบง', \`\${WW_HOTEL}/2019/06/เที่ยวเกาะเหลาเหลียงวันเดย์25.jpg\`],
      ];`,
  `      const HOTEL_IMAGE_KEYS = [
        ['Chom Trang', \`\${WW_HOTEL}/2019/08/${enc('ชมตรัง')}-min.jpg\`],
        ['Maison', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4095006_190814_0048.jpg\`],
        ['Thap Tiang', \`\${WW_HOTEL}/2019/08/${enc('ณ-ทับเที่ยง')}-min.jpg\`],
        ['Eco Inn', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4095029_190814_0002-min-1024x683.jpg\`],
        ['Maitree', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4095010_190814_0055-min-1024x683.jpg\`],
        ['Thammarin', \`\${WW_HOTEL}/2019/08/cc78dd160a4c679e30c2776ecec2aa636_4094997_190814_0071-min-1024x683.jpg\`],
        ['Ruen Rassada', \`\${WW_HOTEL}/2019/08/Superior_sm-min-1024x683.jpg\`],
        ['Koh Ngai Camping', \`\${WW_HOTEL}/2025/08/${enc('เกาะไหง-รีสอร์ท')}-1-Nov-23-Dec-23-11-Jan-24-Apr-24-3.jpg\`],
        ['Koh Ngai Resort', \`\${WW_HOTEL}/2025/08/${enc('เกาะไหง-รีสอร์ท')}-1-Nov-23-Dec-23-11-Jan-24-Apr-24.jpg\`],
        ['Fantasy', \`\${WW_HOTEL}/2025/08/${enc('แฟนตาซี-รีสอร์ท')}-1-May-31-Oct-23-2.jpg\`],
        ['Thapwarin', \`\${WW_HOTEL}/2025/08/P2-${enc('นอนเกาะไหง-โปรแกรมทัวร์-ทับวารินทร์')}.jpg\`],
        ['Libong', \`\${WW_HOTEL}/2019/06/${enc('เที่ยวเกาะเหลาเหลียงวันเดย์25')}.jpg\`],
      ];`,
  'HOTEL_IMAGE_KEYS'
);

// Strip remaining Thai letters outside of intentional baht, keeping English-primary matchers.
// Also fix any leftover Thai-only day parse if previous miss.
t = t.replace(/\/วันที่\\s\*\(\\d\+\)\//g, '/(?:Day|วันที่)\\s*(\\d+)/i');
t = t.replace(/\^วันที่\\s\*\\d\+\\s\*\[·•\\-–—\]\\s\*/g, '^(?:Day|วันที่)\\s*\\d+\\s*[·•\\-–—]\\s*');

fs.writeFileSync(path, t, 'utf8');
const left = [...t.matchAll(/[\u0E00-\u0E7F]/g)].map((m) => m[0]).filter((c) => c !== '฿');
console.log('remaining thai excl baht:', left.length);
if (left.length) {
  t.split(/\r?\n/).forEach((l, i) => {
    if (/[\u0E00-\u0E7F]/.test(l) && !/฿/.test(l.replace(/[\u0E00-\u0E7E\u0E40-\u0E7F]/g, ''))) {
      // show lines that still have Thai letters other than just currency contexts
    }
    if (/[ก-๙]/.test(l)) console.log(String(i + 1) + ':', l.trim().slice(0, 180));
  });
}
