const fs = require('fs');
const j = JSON.parse(fs.readFileSync('c:/xampp/htdocs/1512/data/site.json', 'utf8'));
const out = [];
(function w(v, p) {
  if (typeof v === 'string') {
    if (/[\u0E00-\u0E7F]/.test(v)) out.push({ p, v });
  } else if (Array.isArray(v)) v.forEach((x, i) => w(x, p + '[' + i + ']'));
  else if (v && typeof v === 'object') Object.keys(v).forEach((k) => w(v[k], p + '.' + k));
})(j, '$');
console.log('count', out.length);
out.forEach((o) => console.log(o.p + ' => ' + JSON.stringify(o.v).slice(0, 200)));
