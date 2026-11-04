const fs=require('fs'),path=require('path');
const map=require('./_en_map.json');
const p=path.join(__dirname,'..','data','site.json');
const data=JSON.parse(fs.readFileSync(p,'utf8'));
let n=0;
(function w(o){if(Array.isArray(o)){o.forEach((x,i)=>{if(typeof x==='string'){if(map[x]!==undefined){o[i]=map[x];n++}}else w(x)})}
else if(o&&typeof o==='object'){for(const k in o){const v=o[k];if(typeof v==='string'){if(map[v]!==undefined){o[k]=map[v];n++}}else w(v)}}})(data);
fs.writeFileSync(p,JSON.stringify(data,null,4),'utf8');
console.log('replaced',n);
const th=/[\u0E00-\u0E7F]/;let left=0;
(function c(o,pa){if(typeof o==='string'){if(th.test(o)){left++;console.log('LEFT',pa,o.slice(0,80))}}else if(o&&typeof o==='object')for(const k in o)c(o[k],pa+'.'+k)})(data,'');
console.log('remaining Thai strings',left);
