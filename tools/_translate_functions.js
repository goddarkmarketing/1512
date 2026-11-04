const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/includes/functions.php';
let t = fs.readFileSync(path, 'utf8');

const pairs = [
  ['/* Auto-generated — อัปเดตจากระบบหลังบ้าน */', '/* Auto-generated — updated from admin */'],
  ["'title' => 'แพ็คเกจทัวร์ตรัง'", "'title' => 'Trang tour packages'"],
  [
    "'lead' => 'รวมแพ็คเกจทัวร์เที่ยวเมืองตรัง หลากหลายบริการครบในที่เดียว ตั้งแต่เรือ ที่พัก รถ ตั๋วเรือ ไปจนถึงสัมมนาและสถานที่ถ่ายทำ'",
    "'lead' => 'All-in-one Trang tour packages — boats, stays, transfers, ferry tickets, seminars, and filming locations'",
  ],
  ["'title' => 'แพ็กเกจไปเช้า เย็นกลับ'", "'title' => 'Day-trip packages'"],
  [
    "'lead' => 'ไปเช้าเย็นกลับ ถ้ำมรกต เกาะกระดาน เกาะแหวน เกาะเชือก และเกาะรอก — เริ่มต้น 650 บาท ครบเรือ ไกด์ อาหาร และประกัน'",
    "'lead' => 'Day trips to Emerald Cave, Koh Kradan, Koh Haew, Koh Chueak, and Koh Rok — from 650 THB including boat, guide, meals, and insurance'",
  ],
  ["'eyebrow' => 'พักค้างคืน'", "'eyebrow' => 'Overnight stays'"],
  ["'title' => 'แพ็กเกจ 2 วัน 1 คืน'", "'title' => '2 days 1 night packages'"],
  [
    "'lead' => 'เที่ยวทะเลเต็มอิ่ม 2 วัน 1 คืน รวมเรือ ที่พัก อาหาร และทีมงานดูแลตลอดทริป — ราคาเริ่มต้น 1,275 บาทต่อท่าน'",
    "'lead' => 'A full sea getaway for 2 days 1 night — boat, stay, meals, and on-trip support from 1,275 THB per person'",
  ],
  ["'eyebrow' => 'พักยาวขึ้น'", "'eyebrow' => 'Longer stays'"],
  ["'title' => 'แพ็กเกจ 3 วัน 2 คืน'", "'title' => '3 days 2 nights packages'"],
  [
    "'lead' => 'เที่ยวทะเลตรังแบบสบาย ๆ 3 วัน 2 คืน ครบเรือ ที่พัก อาหาร กิจกรรมทะเล — ราคาเริ่มต้น 1,300 บาทต่อท่าน'",
    "'lead' => 'A relaxed Trang sea trip for 3 days 2 nights — boat, stay, meals, and activities from 1,300 THB per person'",
  ],
  ["'eyebrow' => 'ทริปยาวพิเศษ'", "'eyebrow' => 'Extended trips'"],
  ["'title' => 'แพ็กเกจ 4 วัน 3 คืน'", "'title' => '4 days 3 nights packages'"],
  [
    "'lead' => 'เที่ยวทะเลตรังครบจบ 4 วัน 3 คืน รวมทริปทะเล ที่พัก ทัวร์เมือง — ราคาเริ่มต้น 1,850 บาทต่อท่าน'",
    "'lead' => 'See Trang in full over 4 days 3 nights — sea trips, stays, and city tours from 1,850 THB per person'",
  ],
  ["'eyebrow' => 'จองง่ายใน 4 ขั้นตอน'", "'eyebrow' => 'Book in 4 easy steps'"],
  [
    "'title' => 'จองเรือเที่ยวทะเลตรังใช้เวลาไม่ถึง 2 นาที'",
    "'title' => 'Book a Trang sea trip in under 2 minutes'",
  ],
  [
    "'lead' => 'ระบบคำนวณราคาให้อัตโนมัติ จากนั้นกดปุ่มเดียวเปิด LINE @talaytrang ติดต่อแอดมินได้ทันที'",
    "'lead' => 'Prices calculate automatically — then open LINE @talaytrang in one tap to chat with our team'",
  ],
  ["'eyebrow' => 'รีวิวลูกค้า'", "'eyebrow' => 'Guest reviews'"],
  ["'title' => 'เสียงจริงจากทริปจริง'", "'title' => 'Real voices from real trips'"],
  [
    "'lead' => 'ลูกค้าหลายร้อยทริปบอกต่อ ทั้งครอบครัว คู่รัก เพื่อนกลุ่ม และบริษัท'",
    "'lead' => 'Hundreds of trips recommended by families, couples, friend groups, and companies'",
  ],
  ["'eyebrow' => 'TikTok ล่าสุด'", "'eyebrow' => 'Latest on TikTok'"],
  [
    "'title' => 'ทะเลตรังในมุมที่คุณยังไม่เคยเห็น'",
    "'title' => 'Trang’s sea from angles you haven’t seen'",
  ],
  ["'lead' => 'อัปเดตทุกสัปดาห์ที่'", "'lead' => 'Updated every week at'"],
  ["'eyebrow' => 'ออฟฟิศมีตัวตนจริง'", "'eyebrow' => 'A real local office'"],
  [
    "'title' => 'มาเจอเราได้จริงที่กันตัง จังหวัดตรัง'",
    "'title' => 'Visit us in Kantang, Trang'",
  ],
  ["'infoTitle' => 'มั่นใจได้ทุกทริป'", "'infoTitle' => 'Confidence on every trip'"],
  [
    "'infoLead' => 'เรามีออฟฟิศตั้งอยู่จริงที่อำเภอกันตัง เปิดบริการทุกวัน คุณสามารถนัดเข้ามาดูเรือก่อนตกลงจองได้'",
    "'infoLead' => 'We have a real office in Kantang district, open daily. You can visit and inspect the boat before booking.'",
  ],
  ["'eyebrow' => 'พร้อมออกทะเลแล้วใช่ไหม'", "'eyebrow' => 'Ready to head out to sea?'"],
  [
    "'title' => 'เลือกเรือ คำนวณราคา และจองผ่าน LINE ได้เลย'",
    "'title' => 'Pick a boat, get a price, and book on LINE'",
  ],
  [
    "'lead' => 'ทีมงานแอดมินตอบกลับใน LINE รวดเร็ว ดูแลคุณตั้งแต่ก่อนเที่ยวจนจบทริป'",
    "'lead' => 'Our team replies quickly on LINE and supports you from planning through the end of your trip'",
  ],
  [
    '/** แปลงลิงก์ share Facebook → URL เพจจริง (สำหรับ Page Plugin) */',
    '/** Convert Facebook share links → real page URL (for Page Plugin) */',
  ],
  ["$parts[1] ?? 'จอง'", "$parts[1] ?? 'Book'"],
  ["['name' => 'ที่พัก', 'rooms' => []]", "['name' => 'Accommodation', 'rooms' => []]"],
  [": 'จอง';", ": 'Book';"],
  ["'/^วันที่\\s*(\\d+)/u'", "'/^(?:Day|วันที่)\\s*(\\d+)/iu'"],
  ["('วันที่ ' . ($block['day'] ?? ''))", "('Day ' . ($block['day'] ?? ''))"],
  ["'boat' => 'เรือนำเที่ยว'", "'boat' => 'Tour boat'"],
  ["'food' => 'อาหารบุฟเฟ่ต์'", "'food' => 'Buffet meals'"],
  ["'guide' => 'พนักงานนำเที่ยว'", "'guide' => 'Tour guide'"],
  ["'snorkel' => 'อุปกรณ์ดำน้ำตื้น'", "'snorkel' => 'Snorkeling gear'"],
  ["'park' => 'ค่าเข้าอุทยาน'", "'park' => 'National park fee'"],
  ["'insurance' => 'ประกันอุบัติเหตุ'", "'insurance' => 'Accident insurance'"],
  ["'coffee' => 'กาแฟ'", "'coffee' => 'Coffee'"],
  ["'snack' => 'ของว่าง'", "'snack' => 'Snacks'"],
  ["'lifejacket' => 'เสื้อชูชีพ'", "'lifejacket' => 'Life jacket'"],
  ["'อัปโหลดไม่สำเร็จ'", "'Upload failed'"],
  ["'รองรับเฉพาะ JPG, PNG, WebP, GIF'", "'Only JPG, PNG, WebP, and GIF are supported'"],
  ["'ไฟล์ใหญ่เกิน 5MB'", "'File exceeds 5MB'"],
  ["'ย้ายไฟล์ไม่สำเร็จ'", "'Could not move uploaded file'"],
  [
    '/** แปลง Markdown ง่าย ๆ เป็น article body blocks */',
    '/** Convert simple Markdown into article body blocks */',
  ],
  [
    '/** เวลาจากชื่อไฟล์แบ็คอัพ เช่น 20260602-163045_manual.zip */',
    '/** Timestamp from backup filename, e.g. 20260602-163045_manual.zip */',
  ],
  [
    "'ระบบสำรองให้ (ตอนกดบันทึก — ข้อความอย่างเดียว)'",
    "'Auto backup (on save — text only)'",
  ],
  ["'สำรองเอง (ครบทุกส่วน)'", "'Manual backup (full)'"],
  ["'สำรองเอง (ข้อความอย่างเดียว)'", "'Manual backup (text only)'"],
  [
    '/** แฮชเนื้อหาจริง (ไม่รวม meta.updated) — กันสำรองซ้ำเมื่อกดบันทึกหลายครั้งโดยไม่ได้แก้อะไร */',
    '/** Hash of real content (excluding meta.updated) — avoid duplicate backups on unchanged saves */',
  ],
  [
    '/** ให้ไฟล์ fallback บนหน้าเว็บตรงกับ site.json ก่อนสำรอง */',
    '/** Sync frontend fallback with site.json before backup */',
  ],
  ["'เซิร์ฟเวอร์ไม่รองรับ ZipArchive'", "'Server does not support ZipArchive'"],
  ["'ไม่พบ data/site.json'", "'data/site.json not found'"],
  ["'สร้างไฟล์ ZIP ไม่สำเร็จ'", "'Could not create ZIP file'"],
  ["'สร้างแบ็คอัพไม่สำเร็จ'", "'Could not create backup'"],
  ["'คัดลอก site.json ไม่สำเร็จ'", "'Could not copy site.json'"],
  ["'ชื่อไฟล์ไม่ถูกต้อง'", "'Invalid filename'"],
  ["'ไม่พบไฟล์แบ็คอัพ'", "'Backup file not found'"],
  ["'อ่านไฟล์ไม่สำเร็จ'", "'Could not read file'"],
  ["'JSON ไม่ถูกต้อง'", "'Invalid JSON'"],
  ["'เขียน site.json ไม่สำเร็จ'", "'Could not write site.json'"],
  ["'เปิด ZIP ไม่สำเร็จ'", "'Could not open ZIP'"],
  ["'ไม่พบ site.json ใน ZIP'", "'site.json not found in ZIP'"],
  ["'site.json ใน ZIP ไม่ถูกต้อง'", "'Invalid site.json in ZIP'"],
];

let missing = [];
for (const [from, to] of pairs) {
  if (!t.includes(from)) {
    missing.push(from.slice(0, 80));
    continue;
  }
  t = t.split(from).join(to);
}
fs.writeFileSync(path, t, 'utf8');
const left = (t.match(/[\u0E00-\u0E7F]/g) || []).length;
console.log('missing pairs:', missing.length);
missing.forEach((m) => console.log(' -', m));
console.log('remaining thai chars:', left);
