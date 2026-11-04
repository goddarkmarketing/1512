const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/program.html';
let t = fs.readFileSync(path, 'utf8');

const pairs = [
  ['/(?:Day|วันที่)\\s*(\\d+)/i', '/Day\\s*(\\d+)/i'],
  ['/^(?:Day|วันที่)\\s*\\d+\\s*[·•\\-–—]\\s*/i', '/^Day\\s*\\d+\\s*[·•\\-–—]\\s*/i'],
  ['/(?:Day|วันที่)\\s*\\d+/i', '/Day\\s*\\d+/i'],
  ['/((?:Breakfast|อาหารเช้า).+)/i', '/(Breakfast.+)/i'],
  [
    '/(?:Day|วันที่)\\s*1|tuk-?tuk.*(?:Day|วันที่)\\s*1|airport pickup|airport transfer|tuk-?tuk tour/i',
    '/Day\\s*1|tuk-?tuk.*Day\\s*1|airport pickup|airport transfer|tuk-?tuk tour/i',
  ],
  ['/(?:Day|วันที่)\\s*3|city tour/i', '/Day\\s*3|city tour/i'],
  ['/breakfast|อาหารเช้า/i', '/breakfast/i'],
  [
    '/return to (shore|pier|mainland)|return boat|long-?tail.*return|กลับฝั่ง|กลับท่า|เรือกลับ/i',
    '/return to (shore|pier|mainland)|return boat|long-?tail.*return/i',
  ],
  [
    '/room|hotel|accommodation|lodging|stay|ห้องพัก|ที่พัก|ค่าที่พัก/i',
    '/room|hotel|accommodation|lodging|stay/i',
  ],
  [
    '/boat|lunch|buffet|snorkel|diving|gear|guide|staff|park fee|insurance|coffee|snack|drinking water|ice|pickup|pick-up|van transfer|city transfer|เรือ|อาหารเที่ยง|บุฟเฟ่ต์|ดำน้ำ|ไกด์|อุทยาน|ประกัน/i',
    '/boat|lunch|buffet|snorkel|diving|gear|guide|staff|park fee|insurance|coffee|snack|drinking water|ice|pickup|pick-up|van transfer|city transfer/i',
  ],
  ['/(\\d+)\\s*(?:meals?|มื้อ)/i', '/(\\d+)\\s*meals?/i'],
  ['/van\\s*city tour|รถตู้\\s*city tour/i', '/van\\s*city tour/i'],
  [
    '/(?:room|hotel|accommodation|lodging).{0,20}2\\s*nights?|ห้องพัก\\s*2\\s*คืน|ที่พัก\\s*2\\s*คืน|ค่าที่พัก\\s*2/i',
    '/(?:room|hotel|accommodation|lodging).{0,20}2\\s*nights?/i',
  ],
  ['/\\s*2\\s*(?:nights?|คืน)/i', '/\\s*2\\s*nights?/i'],
  [
    '/koh\\s*(?:hai|ngai).{0,12}2\\s*nights?|เกาะไหง\\s*2\\s*คืน/i',
    '/koh\\s*(?:hai|ngai).{0,12}2\\s*nights?/i',
  ],
  ['/van transfer|shuttle van|รถตู้รับส่ง/i', '/van transfer|shuttle van/i'],
  ['/koh\\s*rok|speed\\s*boat|เกาะรอก|สปีดโบ/i', '/koh\\s*rok|speed\\s*boat/i'],
  ['/ไม่รวม/', '/not included|excluded/i'],
  ['/(\\d+)\\s*วัน/', '/(\\d+)\\s*days?/i'],
];

for (const [from, to] of pairs) {
  if (!t.includes(from)) {
    console.log('MISS', from.slice(0, 60));
    continue;
  }
  t = t.split(from).join(to);
  console.log('OK', from.slice(0, 40));
}

fs.writeFileSync(path, t, 'utf8');
const leftLines = t.split(/\r?\n/).filter((l) => /[ก-๙]/.test(l));
console.log('lines with thai letters:', leftLines.length);
leftLines.forEach((l) => console.log(l.trim().slice(0, 180)));
