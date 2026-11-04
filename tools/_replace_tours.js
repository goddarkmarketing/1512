const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const gallery = [
  'assets/uploads/tours/phi-phi-early-bird/img-0.jpg',
  'assets/uploads/tours/phi-phi-early-bird/img-4.jpg',
  'assets/uploads/tours/phi-phi-early-bird/img-5.jpg',
  'assets/uploads/tours/phi-phi-early-bird/img-3.jpg',
  'assets/uploads/tours/phi-phi-early-bird/img-1.jpg',
  'assets/uploads/tours/phi-phi-early-bird/img-2.jpg',
  'assets/uploads/tours/phi-phi-early-bird/img-6.jpg',
];

const tour = {
  id: 'pp-early-bird-4islands',
  name: 'Early Bird Phi Phi Islands + 4 Islands',
  image: gallery[0],
  route: 'Maya Bay · Pileh Lagoon · Phi Phi · Bamboo Island · Chicken Island',
  stops: [
    'Tup Island',
    'Maya Bay',
    'Pileh Lagoon',
    'Phi Phi Leh',
    'Viking Cave',
    'Monkey Bay',
    'Phi Phi Don',
    'Bamboo Island',
    'Chicken Island',
  ],
  duration: '06:00 AM – 03:30 PM',
  basePrice: 2800,
  childPrice: 2500,
  boats: ['speedboat'],
  desc:
    'By speedboat — sightseeing blacktip reef sharks nursery at Maya Bay. Early departure from Nopparat Thara Pier with hotel transfer, snorkeling, swimming, and lunch on Phi Phi Don.',
  ribbon: 'From 2,800 ฿',
  rating: 4.9,
  reviewCount: '1.2k',
  oldPrice: 0,
  stars: 5,
  packageCode: 'PP-EB01',
  gallery,
  galleryCaption: 'Maya Bay · Blacktip reef sharks · Phi Phi Islands + 4 Islands',
  licenseNo: '',
  itinerary: [
    { time: '05:00', title: 'Hotel transfer', text: 'Pickup from your hotel' },
    { time: '05:30', title: 'Check-in', text: 'Check-in at Krabi Sunrise Tours' },
    { time: '06:00', title: 'Nopparat Thara Pier', text: 'Departure from Nopparat Thara Pier' },
    { time: '06:15', title: 'Tup Island', text: 'Coffee and tea on the beach' },
    {
      time: '08:00',
      title: 'Maya Bay',
      text: 'Relax on the beach, including sightseeing at the blacktip reef shark nursery',
    },
    { time: '09:45', title: 'Pileh Lagoon', text: 'Swimming' },
    { time: '10:30', title: 'Phi Phi Leh', text: 'Snorkeling' },
    { time: '10:45', title: 'Viking Cave', text: 'Sightseeing' },
    { time: '11:00', title: 'Monkey Bay', text: 'Sightseeing' },
    { time: '11:15', title: 'Phi Phi Don', text: 'Lunch' },
    {
      time: '13:00',
      title: 'Bamboo Island',
      text: 'Snorkeling, swimming and relaxing on the beach',
    },
    { time: '15:00', title: 'Chicken Island', text: 'Sightseeing' },
    { time: '15:30', title: 'Nopparat Thara Pier', text: 'Arrival back at the pier' },
  ],
  season: 'Early bird speedboat · Daily · 06:00 AM – 03:30 PM',
  inclusions: [
    'Hotel transfer',
    'Certified tour guide',
    'Travel insurance',
    'Life jackets',
    'Drinks and snacks on board',
    'Lunch at a local restaurant',
    'Snorkeling equipment / mouthpiece',
  ],
  highlights: [
    'Stunning views — breathtaking scenery',
    'Close encounter — get up close with sharks',
    'Crystal clear water — snorkel in crystal waters',
    'Snorkeling spot — explore vibrant marine life',
  ],
  experiences: [
    'Visit Maya Bay',
    'Explore Pileh Lagoon',
    'Viking Cave and Loh Samah Bay',
    'Snorkeling with colorful fish',
    'See the blacktip reef shark nursery',
    'Enjoy onboard refreshments',
  ],
  priceMeetingNote: 'Includes hotel transfer · Departs Nopparat Thara Pier',
  priceNotes: [
    'Adult: 2,800 THB',
    'Child (4–11 years old): 2,500 THB',
    'Infant (1–3 years old): Free of charge',
    'Excluding National Park Fee — Adult 400 THB / Child (4–11) 200 THB',
    'Tour highlights: Maya Bay shark nursery, Pileh Lagoon, snorkeling, Bamboo Island',
    'Restrictions: pregnant women; heart disease; asthma; bone disease; children under 3; wheelchair users; elderly people',
  ],
  warning:
    'The tour program may be changed or cancelled due to weather conditions without prior notice. Brochure lists infants aged 1–3 as free of charge, but also lists children under 3 under restrictions — please confirm age eligibility with the team before booking.',
};

data.programs = [tour];
data.packages2d1n = [];
data.packages3d2n = [];
data.packages4d3n = [];

if (data.homeSections) {
  data.homeSections.boats = {
    eyebrow: 'One-day trips',
    title: 'Early Bird Phi Phi + 4 Islands',
    lead:
      'By speedboat from Nopparat Thara Pier — Maya Bay shark nursery, Pileh Lagoon, snorkeling, and Bamboo Island. From 2,800 THB per adult.',
  };
  data.homeSections.programs = {
    eyebrow: 'Overnight stays',
    title: 'Overnight packages',
    lead: 'Overnight packages coming soon — message us on LINE for custom multi-day trips.',
  };
  data.homeSections.packages3d2n = {
    eyebrow: 'Longer stays',
    title: '3 days 2 nights',
    lead: 'Ask our team on LINE for longer Krabi / Phi Phi itineraries.',
  };
  data.homeSections.packages4d3n = {
    eyebrow: 'Extended trips',
    title: '4 days 3 nights',
    lead: 'Custom extended trips available on request via LINE @ndpoolvilla.',
  };
  data.homeSections.hero = {
    title: 'Krabi & Phi Phi tour packages',
    lead:
      'Speedboat day trips from Ao Nang / Nopparat Thara — Maya Bay, Phi Phi Islands, snorkeling, and more.',
  };
}

if (Array.isArray(data.services)) {
  data.services = data.services.map((s) => {
    if (!s || typeof s !== 'object') return s;
    if (/trang|talay/i.test(String(s.title || ''))) {
      return {
        ...s,
        title: String(s.title)
          .replace(/Trang/gi, 'Krabi')
          .replace(/Talay Trang/gi, 'Suwan Travel'),
      };
    }
    return s;
  });
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log('Replaced tours: programs=1, overnight=0');
