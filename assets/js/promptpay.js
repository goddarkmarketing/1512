/* PromptPay EMV QR (Thai bank apps). Amount is the full charge, in THB. */
(function (global) {
  function tlv(id, value) {
    const v = String(value);
    return id + String(v.length).padStart(2, '0') + v;
  }

  function crc16(payload) {
    let crc = 0xFFFF;
    for (let i = 0; i < payload.length; i++) {
      crc ^= payload.charCodeAt(i) << 8;
      for (let b = 0; b < 8; b++) {
        crc = (crc & 0x8000) ? ((crc << 1) ^ 0x1021) : (crc << 1);
        crc &= 0xFFFF;
      }
    }
    return crc.toString(16).toUpperCase().padStart(4, '0');
  }

  function normalizeId(raw) {
    const digits = String(raw || '').replace(/\D/g, '');
    if (digits.length === 13 && !digits.startsWith('0')) {
      return { tag: '02', value: digits };
    }
    let mobile = digits;
    if (mobile.startsWith('66') && mobile.length === 11) mobile = '0' + mobile.slice(2);
    if (mobile.startsWith('0') && mobile.length === 10) mobile = '0066' + mobile.slice(1);
    if (!mobile.startsWith('0066')) mobile = '0066' + mobile.replace(/^0+/, '');
    return { tag: '01', value: mobile };
  }

  function payload(target, amount) {
    const id = normalizeId(target);
    const amt = Number(amount);
    const money = (Number.isFinite(amt) ? amt : 0).toFixed(2);
    const merchant = tlv('29', tlv('00', 'A000000677010111') + tlv(id.tag, id.value));
    const body = tlv('00', '01')
      + tlv('01', '12')
      + merchant
      + tlv('53', '764')
      + tlv('54', money)
      + tlv('58', 'TH');
    const withCrcMark = body + '6304';
    return withCrcMark + crc16(withCrcMark);
  }

  global.TTPromptPay = { payload };
})(window);
