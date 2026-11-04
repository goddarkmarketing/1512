const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const gallery = [
  'assets/uploads/tours/phi-phi-maya-bamboo/img-0.jpg', // Maya Bay
  'assets/uploads/tours/phi-phi-maya-bamboo/img-1.jpg', // Pileh Lagoon & Speedboat
  'assets/uploads/tours/phi-phi-maya-bamboo/img-2.jpg', // Viking Cave
  'assets/uploads/tours/phi-phi-maya-bamboo/img-3.jpg', // Snorkeling & Tropical Fish
  'assets/uploads/tours/phi-phi-maya-bamboo/img-4.jpg', // Monkey Beach
  'assets/uploads/tours/phi-phi-maya-bamboo/img-5.jpg', // Phi Phi Don
  'assets/uploads/tours/phi-phi-maya-bamboo/img-6.jpg', // Bamboo Island
  'assets/uploads/tours/phi-phi-maya-bamboo/img-7.jpg', // Thai Buffet Lunch
];

const tour = {
  id: 'pp-maya-bamboo-day',
  name: 'Phi Phi Islands, Maya Bay & Bamboo Island',
  image: gallery[0],
  route: 'Maya Bay · Pileh Lagoon · Phi Phi Leh · Viking Cave · Monkey Beach · Phi Phi Don · Bamboo Island',
  stops: [
    'Maya Bay',
    'Pileh Lagoon',
    'Phi Phi Leh',
    'Viking Cave',
    'Monkey Beach',
    'Phi Phi Don',
    'Bamboo Island',
  ],
  duration: '09:00–16:00',
  basePrice: 2500,
  childPrice: 2000,
  boats: ['speedboat'],
  desc:
    'One-day speedboat tour from Krabi. Visit famous Maya Bay, swim at Pileh Lagoon, snorkel around Phi Phi Leh, see Viking Cave and Monkey Beach from the boat, enjoy a Thai buffet lunch on Phi Phi Don, and relax on Bamboo Island.',
  ribbon: 'From 2,500 ฿',
  rating: 4.9,
  reviewCount: '980',
  oldPrice: 0,
  stars: 5,
  packageCode: 'PP-MB01',
  gallery,
  galleryCaption:
    'Maya Bay · Pileh Lagoon · Viking Cave · Snorkeling · Monkey Beach · Phi Phi Don · Bamboo Island · Lunch',
  licenseNo: '',
  itinerary: [
    {
      time: '07:30',
      title: 'Hotel pickup',
      text: 'Pickup from your hotel in Krabi (approximately 07:30–08:40) and transfer to the pier',
    },
    {
      time: '08:40',
      title: 'Arrival at the pier',
      text: 'Meet the crew and enjoy coffee or tea before departure',
    },
    {
      time: '09:15',
      title: 'Speedboat departure',
      text: 'Travel by speedboat to the Phi Phi Islands',
    },
    {
      time: '10:15',
      title: 'Walk to Maya Bay',
      text: 'Follow the wooden walkway from the floating pier to Maya Bay',
    },
    {
      time: '10:30',
      title: 'Maya Bay',
      text:
        'Explore the famous beach from the film “The Beach.” Enjoy the scenery and take photos of white sand, turquoise water, and limestone cliffs. Swimming is not permitted at Maya Bay',
    },
    {
      time: '11:20',
      title: 'Pileh Lagoon',
      text: 'Swim in clear turquoise waters surrounded by towering limestone cliffs',
    },
    {
      time: '12:00',
      title: 'Phi Phi Leh snorkeling',
      text: 'Discover tropical fish and coral reefs during a snorkeling stop around Phi Phi Leh',
    },
    {
      time: '12:30',
      title: 'Viking Cave',
      text: 'Sightseeing and photos of Viking Cave from the boat',
    },
    {
      time: '12:45',
      title: 'Monkey Beach',
      text:
        'Look for wild monkeys along the shoreline from the boat. Sightings depend on tides and conditions. Landing is not included',
    },
    {
      time: '13:00',
      title: 'Phi Phi Don — buffet lunch',
      text: 'Thai-style buffet lunch at Arida Restaurant on Phi Phi Don',
    },
    {
      time: '14:00',
      title: 'Bamboo Island',
      text: 'Relax on the white sand beach and enjoy the island scenery',
    },
    {
      time: '15:00',
      title: 'Return journey',
      text:
        'Travel back towards Krabi, with a possible drop-off at Railay for guests staying there',
    },
    {
      time: '15:45',
      title: 'Nopparat Thara Pier',
      text: 'Arrive back at the pier (around 15:45–16:00)',
    },
  ],
  season: 'One-day speedboat · Tour hours 09:00–16:00 · Hotel pickup approx. 07:30–08:40',
  inclusions: [
    'Round-trip hotel transfers',
    'Speedboat transportation',
    'Drinking water',
    'Seasonal fruit',
    'Soft drinks',
    'Coffee and tea at the pier',
    'Thai-style buffet lunch',
    'Snorkeling equipment',
    'Life jacket',
    'Professional English-speaking guide',
    'Basic travel accident insurance (injuries during the trip; pre-existing conditions excluded)',
  ],
  highlights: [
    'Maya Bay scenery and photos',
    'Swim at Pileh Lagoon',
    'Snorkel around Phi Phi Leh',
    'Viking Cave sightseeing from the boat',
    'Monkey Beach wildlife viewing',
    'Buffet lunch on Phi Phi Don',
    'Relax on Bamboo Island',
  ],
  experiences: [
    'Visit Maya Bay',
    'Swim at Pileh Lagoon',
    'Snorkel with tropical fish',
    'See Viking Cave from the boat',
    'Watch for monkeys at Monkey Beach',
    'Thai buffet lunch on Phi Phi Don',
    'Beach time at Bamboo Island',
  ],
  priceMeetingNote: 'Includes round-trip hotel transfers · Return to Nopparat Thara Pier',
  priceNotes: [
    'Adult: THB 2,500 per person',
    'Child aged 4–10: THB 2,000 per person',
    'Infant aged 1–3: Free of charge (subject to operator minimum-age policy)',
    'National park fees not included — Adult THB 400 / Child aged 4–10 THB 200',
    'What to bring: towel, waterproof bag, swimwear, reef-safe sunscreen, sun hat, sunglasses, flip-flops/water shoes, camera, quick-drying clothing',
    'Important: no swimming at Maya Bay; no feeding/touching monkeys; wear life jacket on boat and while snorkeling; Maya Bay may have seasonal closure',
    'Restrictions: pregnant guests; asthma; heart disease; bone/back/neck problems; wheelchair users; elderly guests — confirm eligibility before booking',
  ],
  warning:
    'The itinerary and timings may change depending on weather, sea conditions and operational requirements. Confirm Maya Bay seasonal closure dates and minimum child age with the operator before booking.',
};

const existing = Array.isArray(data.programs) ? data.programs : [];
const withoutDup = existing.filter((p) => p && p.id !== tour.id);
data.programs = [...withoutDup, tour];

if (data.homeSections?.boats) {
  data.homeSections.boats = {
    eyebrow: 'One-day trips',
    title: 'Phi Phi & Maya Bay day trips',
    lead:
      'Speedboat day trips from Krabi — Early Bird Phi Phi + 4 Islands from 2,800 THB, or Maya Bay & Bamboo Island from 2,500 THB.',
  };
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log(
  'programs:',
  data.programs.map((p) => p.id + ' @' + p.basePrice).join(', ')
);
