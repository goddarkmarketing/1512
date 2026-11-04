const fs = require('fs');
const path = require('path');
function walk(d, a = []) {
  for (const e of fs.readdirSync(d, { withFileTypes: true })) {
    const p = path.join(d, e.name);
    if (e.isDirectory()) walk(p, a);
    else if (/\.(php|js|md)$/.test(e.name)) a.push(p);
  }
  return a;
}
const files = walk('c:/xampp/htdocs/1512/admin');
files.push('c:/xampp/htdocs/1512/ADMIN.md');
for (const f of files) {
  const t = fs.readFileSync(f, 'utf8');
  const n = (t.match(/[\u0E00-\u0E7F]/g) || []).length;
  if (n) console.log(n + '\t' + f);
}
