const fs = require('fs');
const path = require('path');
const file = path.join(__dirname, '..', 'data', 'site.json');
const data = JSON.parse(fs.readFileSync(file, 'utf8'));

data.heroSlides = [
  {
    src: 'assets/uploads/tours/phi-phi-maya-bamboo/img-0.jpg',
    alt: 'Phi Phi Islands and Maya Bay, Krabi'
  },
  {
    src: 'assets/uploads/tours/krabi-4islands/img-0.jpg',
    alt: 'Krabi 4 Islands day trip'
  },
  {
    src: 'assets/uploads/tours/hong-kayak-sunset/img-0.jpg',
    alt: 'Hong Island kayaking and sunset'
  },
  {
    src: 'assets/uploads/tours/phi-phi-sunset/img-0.jpg',
    alt: 'Phi Phi sunset tour'
  },
  {
    src: 'assets/uploads/tours/hong-paradise-speedboat/img-0.jpg',
    alt: 'Hong Island Paradise by speedboat'
  }
];

data.images.heroHome = 'assets/uploads/tours/krabi-4islands/img-0.jpg';
data.seo.index.ogImage = 'assets/uploads/tours/krabi-4islands/img-0.jpg';

data.homeSections = {
  hero: {
    title: 'Krabi & Phi Phi day trips from Ao Nang',
    lead: 'Speedboat and longtail tours, plus daily car rental — book on LINE @ndpoolvilla.'
  },
  boats: {
    eyebrow: 'Tour packages',
    title: 'Choose your Krabi sea day',
    lead: 'Phi Phi, Maya Bay, Hong Island, Krabi 4 Islands, and sunset / plankton trips — hotel transfers included on most packages.'
  },
  programs: {
    eyebrow: '',
    title: '',
    lead: ''
  },
  packages3d2n: { eyebrow: '', title: '', lead: '' },
  packages4d3n: { eyebrow: '', title: '', lead: '' },
  booking: {
    eyebrow: 'Easy booking',
    title: 'Book in a few messages',
    lead: 'Pick a package, confirm on LINE @ndpoolvilla or WhatsApp, then receive your pickup time.'
  },
  reviews: { eyebrow: '', title: '', lead: '' },
  videos: { eyebrow: '', title: '', lead: '', tiktokHandle: '' },
  office: {
    eyebrow: 'Contact',
    title: 'Visit or message us in Ao Nang',
    infoTitle: 'We’re ready to help',
    infoLead: '1267 Moo 2, Ao Nang, Mueang Krabi, Krabi 81180'
  },
  cta: {
    eyebrow: 'Ready when you are',
    title: 'Plan your Krabi day trip today',
    lead: 'Tours from 1,350 THB · Car rental from 900 THB · LINE @ndpoolvilla'
  }
};

data.meta.updated = new Date().toISOString();
data.meta.note = 'Suwan Travel Krabi — homepage refreshed for day trips and car rental';

fs.writeFileSync(file, JSON.stringify(data, null, 4) + '\n');
console.log('Updated heroSlides (' + data.heroSlides.length + ') and homeSections');
