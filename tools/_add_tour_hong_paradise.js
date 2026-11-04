const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const gallery = Array.from({ length: 8 }, (_, i) => `assets/uploads/tours/hong-paradise-speedboat/img-${i}.jpg`);

const tour = {
  id: 'hong-paradise-speedboat',
  name: 'Hong & Paradise Islands',
  image: gallery[0],
  route: 'Hong Island Beach · 360° Viewpoint · Hong Lagoon · Pak Bia · Lading · Daeng Island',
  stops: [
    'Hong Island Beach',
    'Hong Island 360° Viewpoint',
    'Hong Lagoon',
    'Pak Bia Island',
    'Lading Island',
    'Daeng Island',
  ],
  duration: '09:00–16:00',
  basePrice: 1550,
  childPrice: 1350,
  boats: ['speedboat'],
  desc:
    'By speedboat with buffet lunch included. Discover island beaches, swim in turquoise waters, admire Hong Island’s 360° viewpoint, explore the hidden Hong Lagoon, visit Pak Bia sandbar and snorkel at Lading Island. Optional kayaking package available.',
  ribbon: 'From 1,550 ฿',
  rating: 4.8,
  reviewCount: '720',
  oldPrice: 0,
  stars: 5,
  packageCode: 'HP-SB01',
  gallery,
  galleryCaption:
    'Hong Island beach · Viewpoint · Lagoon · Pak Bia sandbar · Snorkeling · Buffet lunch',
  licenseNo: '',
  itinerary: [
    {
      time: '07:40',
      title: 'Hotel pickup',
      text: 'Pickup from your hotel in Krabi (approximately 07:40–08:45)',
    },
    {
      time: '08:45',
      title: 'Check-in',
      text: 'Register at the Krabi Sunset Tour booth at Nopparat Thara Pier',
    },
    {
      time: '09:00',
      title: 'Departure for Hong Island',
      text: 'Cruise by speedboat to Hong Island Beach',
    },
    {
      time: '09:30',
      title: 'Hong Island Beach',
      text: 'Free time for swimming and sunbathing on the main island',
    },
    {
      time: '11:15',
      title: 'Hong Island 360° Viewpoint',
      text: 'Climb to the viewpoint for panoramic views of the surrounding islands and sea',
    },
    {
      time: '12:00',
      title: 'Buffet lunch',
      text:
        'Thai buffet lunch on Hong Island Beach. Guests on the kayaking package enjoy kayaking from Hong Island Beach to the lagoon',
    },
    {
      time: '13:00',
      title: 'Hong Lagoon',
      text: 'Cruise into the hidden Hong Lagoon',
    },
    {
      time: '13:10',
      title: 'Pak Bia Island',
      text: 'Stop to take photos of the sandbar',
    },
    {
      time: '14:30',
      title: 'Lading Island',
      text: 'Swimming and snorkeling at Lading Island',
    },
    {
      time: '14:45',
      title: 'Return journey',
      text: 'Cruise back to the pier, passing Daeng Island known for its reddish color',
    },
    {
      time: '15:45',
      title: 'Hotel transfer',
      text: 'Transfer back to your hotel (around 15:45–16:00)',
    },
  ],
  season: 'Speedboat · Tour hours ~09:00–16:00 · Hotel pickup approx. 07:40–08:45 · Buffet lunch included',
  inclusions: [
    'Round-trip hotel transfers',
    'Drinking water',
    'Seasonal fruit',
    'Mini Thai buffet lunch',
    'Snorkeling equipment',
    'Life jacket',
    'Professional English-speaking guide',
    'Basic accident insurance',
  ],
  highlights: [
    'Hong Island Beach swimming & sunbathing',
    '360° panoramic viewpoint',
    'Hidden Hong Lagoon',
    'Pak Bia sandbar photos',
    'Snorkeling at Lading Island',
    'Optional kayaking upgrade',
  ],
  experiences: [
    'Swim at Hong Island Beach',
    'Climb the 360° viewpoint',
    'Explore Hong Lagoon',
    'Photograph Pak Bia sandbar',
    'Snorkel at Lading Island',
    'Enjoy buffet lunch on the beach',
  ],
  priceMeetingNote:
    'Includes round-trip hotel transfers · Check-in at Krabi Sunset Tour booth, Nopparat Thara Pier',
  priceNotes: [
    'Speedboat tour — Adult: THB 1,550 / Child (4–11): THB 1,350 / Infant (1–3): Free',
    'With kayaking — Adult: THB 1,850 / Child (4–11): THB 1,650',
    'National park entrance fees are not included',
    'Kayaking package: kayak from Hong Island Beach toward the lagoon after lunch',
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
      'Phi Phi & Maya Bay, Hong Island kayaking & sunset, plus Hong & Paradise Islands by speedboat from 1,550 THB.',
  };
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log(data.programs.map((p) => `${p.id} @${p.basePrice}`).join('\n'));
