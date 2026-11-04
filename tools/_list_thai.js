const fs = require('fs');
const file = process.argv[2];
const t = fs.readFileSync(file, 'utf8');
const lines = t.split(/\n/).filter((l) => /[\u0E00-\u0E7F]/.test(l));
console.log('lines with thai:', lines.length);
lines.forEach((l, i) => console.log(String(i + 1).padStart(3) + ': ' + l.trim().slice(0, 160)));
