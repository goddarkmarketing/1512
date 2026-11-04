const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const gallery = Array.from({ length: 8 }, (_, i) => `assets/uploads/tours/hong-kayak-sunset/img-${i}.jpg`);

const tour = {
  id: 'hong-kayak-sunset',
  name: 'Hong Island Kayaking & Sunset Tour',
  image: gallery[0],
  route: 'Hong Island · 360° Viewpoint · Hong Lagoon · Pak Bia · Lading · Koh Raeng',
  stops: [
    'Hong Island',
    'Hong Island 360° Viewpoint',
    'Hong Lagoon',
    'Pak Bia Island',
    'Lading Island',
    'Koh Raeng',
  ],
  duration: '12:00–20:00',
  basePrice: 2050,
  childPrice: 1850,
  boats: ['longtail'],
  desc:
    'By longtail boat — kayak around Hong Island, snorkel and beach time, panoramic views from the 360° viewpoint, explore Hong Lagoon, then finish with a sunset buffet dinner and night snorkeling for bioluminescent plankton.',
  ribbon: 'From 2,050 ฿',
  rating: 4.9,
  reviewCount: '640',
  oldPrice: 0,
  stars: 5,
  packageCode: 'HK-ST01',
  gallery,
  galleryCaption:
    'Hong Island kayaking · Viewpoint · Lagoon · Sunset buffet · Bioluminescent plankton',
  licenseNo: '',
  itinerary: [
    {
      time: '12:00',
      title: 'Hotel pickup',
      text: 'Pickup from your hotel in Krabi (approximately 12:00–12:45)',
    },
    {
      time: '12:45',
      title: 'Nopparat Thara Pier',
      text: 'Meet the boat crew at Nopparat Thara Pier in Ao Nang',
    },
    {
      time: '13:00',
      title: 'Departure for Hong Island',
      text: 'Cruise to Hong Island by longtail boat',
    },
    {
      time: '13:40',
      title: 'Hong Island',
      text: 'Enjoy kayaking, snorkeling and sunbathing on the main island',
    },
    {
      time: '15:30',
      title: 'Hong Island 360° Viewpoint',
      text: 'Visit the viewpoint for panoramic views of the surrounding islands and sea',
    },
    {
      time: '16:15',
      title: 'Hong Lagoon',
      text: 'Cruise into the hidden lagoon and admire its mangrove forest',
    },
    {
      time: '16:45',
      title: 'Pak Bia Island & Lading Island',
      text: 'Enjoy a sunset buffet dinner',
    },
    {
      time: '18:30',
      title: 'Koh Raeng',
      text: 'Go night snorkeling to see bioluminescent plankton',
    },
    {
      time: '19:10',
      title: 'Return journey',
      text: 'Cruise back to the pier',
    },
    {
      time: '19:40',
      title: 'Hotel transfer',
      text: 'Arrive at the pier and transfer back to your hotel (around 19:40–20:00)',
    },
  ],
  season: 'Longtail boat · Tour time 12:00–20:00 · Sunset dinner & bioluminescent plankton',
  inclusions: [
    'Round-trip hotel transfers',
    'Drinking water and soft drinks',
    'Seasonal fruit',
    'Thai buffet dinner',
    'Snorkeling equipment',
    'Life jacket',
    'Kayaking equipment',
    'Professional English-speaking guide',
    'Basic accident insurance',
  ],
  highlights: [
    'Kayaking at Hong Island',
    'Snorkeling and beach time',
    '360° panoramic viewpoint',
    'Hidden Hong Lagoon',
    'Sunset buffet dinner',
    'Night snorkeling with bioluminescent plankton',
  ],
  experiences: [
    'Explore Hong Island by kayak',
    'Snorkel and sunbathe',
    'Admire views from the 360° viewpoint',
    'Discover Hong Lagoon mangroves',
    'Sunset buffet dinner',
    'See bioluminescent plankton at night',
  ],
  priceMeetingNote: 'Includes round-trip hotel transfers · Nopparat Thara Pier, Ao Nang',
  priceNotes: [
    'Adult: THB 2,050 per person',
    'Child (4–11 years): THB 1,850 per person',
    'Infant (1–3 years): Free of charge',
    'National park entrance fees are not included',
    'Buffet dinner & bioluminescent plankton night snorkeling included',
  ],
  warning:
    'The itinerary is subject to change depending on weather and sea conditions.',
};

const existing = Array.isArray(data.programs) ? data.programs : [];
data.programs = [...existing.filter((p) => p && p.id !== tour.id), tour];

if (data.homeSections?.boats) {
  data.homeSections.boats = {
    eyebrow: 'One-day trips',
    title: 'Krabi day trips by boat',
    lead:
      'Phi Phi & Maya Bay speedboat trips from 2,500 THB, plus Hong Island kayaking & sunset from 2,050 THB.',
  };
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log(data.programs.map((p) => `${p.id} @${p.basePrice}`).join('\n'));
