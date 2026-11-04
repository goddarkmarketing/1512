const fs = require('fs');
const path = require('path');
const dir = 'c:/xampp/htdocs/1512';
const files = [
  'index.html',
  'about.html',
  'article.html',
  'articles.html',
  'boat-book.html',
  'boats.html',
  'booking.html',
  'contact.html',
  'program.html',
  'programs.html',
  'videos.html',
  'packages-2d1n.html',
];
for (const f of files) {
  const t = fs.readFileSync(path.join(dir, f), 'utf8');
  const n = (t.match(/[\u0E00-\u0E7F]/g) || []).length;
  const lang = (t.match(/<html[^>]*lang="([^"]+)"/) || [])[1];
  console.log(f + ': thaiChars=' + n + ' lang=' + lang);
}
const j = JSON.parse(fs.readFileSync(path.join(dir, 'data/site.json'), 'utf8'));
let c = 0;
(function w(v) {
  if (typeof v === 'string') {
    if (/[\u0E00-\u0E7F]/.test(v)) c++;
  } else if (Array.isArray(v)) v.forEach(w);
  else if (v && typeof v === 'object') Object.values(v).forEach(w);
})(j);
console.log('site.json thai strings:', c);
const mapPath = path.join(dir, 'tools/_en_map.json');
console.log('en_map exists:', fs.existsSync(mapPath));
if (fs.existsSync(mapPath)) {
  const m = JSON.parse(fs.readFileSync(mapPath, 'utf8'));
  console.log('en_map keys:', Object.keys(m).length);
}
