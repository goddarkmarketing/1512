const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

data.site = {
  ...data.site,
  brand: 'Suwan Travel',
  brandTh: 'Suwan travel and tour',
  tagline: 'Travel & boat tours in Ao Nang, Krabi',
  phone: '093-7414111',
  phone2: '080-6323483',
  phoneDisplay: '093-7414111, 080-6323483',
  whatsapp: '+66937414111',
  whatsappUrl: 'https://wa.me/66937414111',
  lineId: '@ndpoolvilla',
  lineUrl: 'https://line.me/R/ti/p/%40ndpoolvilla',
  address: 'Ao Nang, Mueang Krabi',
  addressFull: '1267 Moo 2, Ao Nang, Mueang Krabi, Krabi 81180',
  mapEmbed:
    'https://maps.google.com/maps?q=1267+Moo+2+Ao+Nang+Mueang+Krabi+Krabi+81180&hl=en&z=16&output=embed',
};

if (data.homeSections?.office) {
  data.homeSections.office = {
    ...data.homeSections.office,
    eyebrow: 'A real, visitable office',
    title: 'Visit us in Ao Nang, Krabi',
    infoTitle: 'Confidence on every trip',
    infoLead:
      'Our office is in Ao Nang, Mueang Krabi, open daily. You’re welcome to visit before you book.',
  };
}
if (data.homeSections?.booking) {
  data.homeSections.booking = {
    ...data.homeSections.booking,
    lead:
      'Prices calculate automatically — then open LINE @ndpoolvilla in one tap to chat with our team',
  };
}
if (data.homeSections?.cta) {
  data.homeSections.cta = {
    ...data.homeSections.cta,
    lead: 'Our team replies quickly on LINE @ndpoolvilla and supports you from planning through the end of your trip',
  };
}
if (Array.isArray(data.steps)) {
  data.steps = data.steps.map((s) => {
    if (typeof s.desc === 'string' && s.desc.includes('@talaytrang')) {
      return { ...s, desc: s.desc.replaceAll('@talaytrang', '@ndpoolvilla') };
    }
    return s;
  });
}
if (data.about?.office) {
  data.about.office = {
    ...data.about.office,
    eyebrow: 'Visit us at',
    title: 'Suwan Travel · Ao Nang, Krabi',
    infoTitle: 'Heartfelt service at every level',
    infoText:
      'Before you book, you can visit our office in Ao Nang, Krabi and meet the team in person.',
  };
}
if (data.about?.cta) {
  data.about.cta = {
    ...data.about.cta,
    lead: 'Message us on LINE @ndpoolvilla and we’ll recommend the best options for you',
  };
}
if (data.seo) {
  for (const key of Object.keys(data.seo)) {
    const block = data.seo[key];
    if (!block || typeof block !== 'object') continue;
    for (const field of ['title', 'description', 'ogTitle', 'ogDescription']) {
      if (typeof block[field] === 'string') {
        block[field] = block[field]
          .replaceAll('Talay Trang', 'Suwan Travel')
          .replaceAll('@talaytrang', '@ndpoolvilla')
          .replaceAll('Kantang', 'Ao Nang')
          .replaceAll('Trang province', 'Krabi province')
          .replaceAll('Trang sea', 'Krabi sea')
          .replaceAll('Trang boat', 'Krabi boat');
      }
    }
  }
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log('site.json contact updated');
