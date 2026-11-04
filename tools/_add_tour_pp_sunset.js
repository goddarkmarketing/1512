const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const gallery = Array.from({ length: 6 }, (_, i) => `assets/uploads/tours/phi-phi-sunset/img-${i}.jpg`);

const tour = {
  id: 'pp-islands-sunset',
  name: 'Phi Phi Islands Sunset',
  image: gallery[0],
  route: 'Maya Bay · Pileh Lagoon · Phi Phi · Bamboo Island · Poda Island · Plankton',
  stops: [
    'Maya Bay',
    'Pileh Lagoon',
    'Phi Phi Leh',
    'Viking Cave',
    'Monkey Bay',
    'Phi Phi Don',
    'Bamboo Island',
    'Poda Island',
    'Plankton experience',
  ],
  duration: '10:00 AM – 7:30 PM',
  basePrice: 2800,
  childPrice: 2500,
  boats: ['speedboat'],
  desc:
    'By speedboat — lunch, BBQ dinner, sunset & plankton. Explore the Phi Phi Islands, snorkel in crystal-clear waters, relax on beautiful beaches, enjoy a BBQ dinner at sunset on Poda Island, then night snorkeling for bioluminescent plankton.',
  ribbon: 'From 2,800 ฿',
  rating: 4.9,
  reviewCount: '860',
  oldPrice: 0,
  stars: 5,
  packageCode: 'PP-SS01',
  gallery,
  galleryCaption:
    'Maya Bay · Pileh Lagoon · Bamboo Island · Poda sunset BBQ · Bioluminescent plankton',
  licenseNo: '',
  itinerary: [
    { time: '09:00', title: 'Hotel transfer', text: 'Pick-up from your hotel' },
    { time: '09:30', title: 'Check-in', text: 'Check in before departure' },
    { time: '10:00', title: 'Nopparat Thara Pier', text: 'Depart by speedboat' },
    { time: '10:45', title: 'Maya Bay', text: 'Relax on the beach' },
    { time: '11:45', title: 'Pileh Lagoon', text: 'Swim in the emerald lagoon' },
    { time: '12:30', title: 'Phi Phi Leh', text: 'Enjoy snorkeling' },
    { time: '12:45', title: 'Viking Cave', text: 'Sightseeing' },
    { time: '13:00', title: 'Monkey Bay', text: 'Sightseeing' },
    { time: '13:15', title: 'Phi Phi Don', text: 'Enjoy a Thai buffet lunch' },
    {
      time: '15:00',
      title: 'Bamboo Island',
      text: 'Snorkel, swim, and relax on the beach',
    },
    {
      time: '17:00',
      title: 'Poda Island',
      text: 'Enjoy a BBQ dinner and watch the sunset',
    },
    {
      time: '19:00',
      title: 'Plankton experience',
      text: 'Night snorkeling to see bioluminescent plankton',
    },
    {
      time: '19:30',
      title: 'Nopparat Thara Pier',
      text: 'Return to the pier',
    },
  ],
  season: 'Speedboat · 10:00 AM – 7:30 PM · Lunch, BBQ dinner, sunset & plankton',
  inclusions: [
    'Round-trip hotel transfers',
    'Speedboat trip',
    'Professional tour guide',
    'Snorkeling equipment and mouthpiece',
    'Life jacket',
    'Thai buffet lunch',
    'BBQ dinner',
    'Fruits and drinking water',
    'Travel insurance',
  ],
  highlights: [
    'Phi Phi Islands sightseeing',
    'Snorkel in crystal-clear water',
    'Beach time at Bamboo Island',
    'Sunset BBQ dinner on Poda Island',
    'Night snorkeling with bioluminescent plankton',
  ],
  experiences: [
    'Visit Maya Bay and Pileh Lagoon',
    'Snorkel around Phi Phi Leh',
    'Buffet lunch on Phi Phi Don',
    'Relax on Bamboo Island',
    'Sunset BBQ on Poda Island',
    'See bioluminescent plankton at night',
  ],
  priceMeetingNote: 'Includes round-trip hotel transfers · Departs Nopparat Thara Pier',
  priceNotes: [
    'Adult: THB 2,800 per person',
    'Child, aged 4–11: THB 2,500 per person',
    'Infant, aged 1–3: Free of charge*',
    'National park fees not included — Adult THB 400 / Child aged 4–11 THB 200',
    'Bioluminescent plankton visibility depends on natural conditions',
    'Restrictions: pregnant women; heart disease; asthma; bone conditions; wheelchair users; elderly people; children under 3 — confirm eligibility before booking',
  ],
  warning:
    'The tour program may be changed or cancelled due to weather conditions without prior notice. Bioluminescent plankton visibility depends on natural conditions.',
};

const existing = Array.isArray(data.programs) ? data.programs : [];
data.programs = [...existing.filter((p) => p && p.id !== tour.id), tour];

if (data.homeSections?.boats) {
  data.homeSections.boats = {
    eyebrow: 'One-day trips',
    title: 'Krabi day trips by boat',
    lead:
      'Phi Phi day & sunset trips, Hong Island kayaking, and Hong & Paradise Islands — from 1,550 THB.',
  };
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log(data.programs.map((p) => `${p.id} @${p.basePrice}`).join('\n'));
