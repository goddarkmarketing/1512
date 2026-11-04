const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const gallery = Array.from({ length: 8 }, (_, i) => `assets/uploads/tours/phi-phi-maya-sunset4/img-${i}.jpg`);

const tour = {
  id: 'pp-maya-sunset-4islands',
  name: 'Phi Phi Islands + Maya Bay + Sunset 4 Islands',
  image: gallery[0],
  route: 'Bamboo Island · Phi Phi Don · Monkey Beach · Viking Cave · Pileh Lagoon · Maya Bay · Tup/Poda Sunset',
  stops: [
    'Bamboo Island',
    'Phi Phi Don',
    'Monkey Beach',
    'Viking Cave',
    'Pileh Lagoon',
    'Loh Samah Bay',
    'Maya Bay',
    'Tup or Poda Island',
  ],
  duration: '10:00 AM – 7:00 PM',
  basePrice: 3500,
  childPrice: 3000,
  boats: ['speedboat'],
  desc:
    'By speedboat — explore Bamboo Island, Phi Phi Don buffet lunch, Monkey Beach, Viking Cave, Pileh Lagoon, Maya Bay via Loh Samah Bay, then watch the sunset at Tup or Poda Island before returning to Nopparat Thara Pier.',
  ribbon: 'From 3,500 ฿',
  rating: 4.9,
  reviewCount: '540',
  oldPrice: 0,
  stars: 5,
  packageCode: 'PP-MS401',
  gallery,
  galleryCaption:
    'Bamboo Island · Phi Phi Don · Pileh Lagoon · Maya Bay · Sunset at Tup or Poda',
  licenseNo: '',
  itinerary: [
    {
      time: '09:00',
      title: 'Hotel pickup',
      text:
        'Pickup from your hotel in Krabi (approximately 09:00–09:45). Exact time depends on hotel location and will be confirmed before the trip',
    },
    {
      time: '10:00',
      title: 'Departure',
      text:
        'Depart from Nopparat Thara Pier in Ao Nang, with a short stop at Railay Beach to pick up other guests',
    },
    {
      time: '10:45',
      title: 'Bamboo Island',
      text: 'Snorkeling, swimming, sunbathing, or relaxing on the white sandy beach',
    },
    {
      time: '12:15',
      title: 'Phi Phi Don',
      text:
        'Thai buffet lunch at Arida Restaurant, followed by free time for swimming, relaxing, or sightseeing',
    },
    {
      time: '13:30',
      title: 'Monkey Beach',
      text:
        'See monkeys in their natural surroundings. The visit depends on tidal conditions',
    },
    {
      time: '14:00',
      title: 'Viking Cave',
      text:
        'Sightseeing and photos of the limestone cave, known for wall paintings and bird’s nest harvesting',
    },
    {
      time: '14:15',
      title: 'Pileh Lagoon',
      text:
        'Explore crystal-clear turquoise waters surrounded by towering limestone cliffs, with time for snorkeling',
    },
    {
      time: '15:00',
      title: 'Loh Samah Bay & Maya Bay',
      text:
        'Arrive at the floating pier in Loh Samah Bay and walk across to Maya Bay. Admire the white sandy beach and dramatic limestone scenery from the movie “The Beach.”',
    },
    {
      time: '17:30',
      title: 'Sunset at Tup or Poda Island',
      text:
        'Watch the sunset at Tup Island or Poda Island, depending on sea conditions, before returning to Nopparat Thara Pier and transferring back to your hotel',
    },
  ],
  season: 'Speedboat · 10:00 AM – 7:00 PM · Maya Bay + sunset 4 islands',
  inclusions: [
    'Round-trip hotel transfers',
    'Drinking water and soft drinks',
    'Seasonal fruits and snacks',
    'Thai buffet lunch at Arida Restaurant, Phi Phi Don',
    'Snorkeling equipment',
    'Life jacket',
    'Professional English-speaking guide',
    'Basic accident insurance',
  ],
  highlights: [
    'Bamboo Island beach & snorkeling',
    'Buffet lunch on Phi Phi Don',
    'Monkey Beach & Viking Cave sightseeing',
    'Pileh Lagoon snorkeling',
    'Maya Bay via Loh Samah Bay',
    'Sunset at Tup or Poda Island',
  ],
  experiences: [
    'Snorkel and relax at Bamboo Island',
    'Lunch at Arida Restaurant',
    'See Monkey Beach and Viking Cave',
    'Swim/snorkel at Pileh Lagoon',
    'Visit Maya Bay',
    'Watch sunset on Tup or Poda Island',
  ],
  priceMeetingNote:
    'Includes round-trip hotel transfers · Departs Nopparat Thara Pier (Ao Nang), with Railay pickup stop',
  priceNotes: [
    'Adult: 3,500 THB',
    'Child (4–11 years): 3,000 THB',
    'Infant (1–3 years): Free of charge',
    'National park entrance fees are not included',
    'Monkey Beach visit depends on tidal conditions',
    'Sunset stop is Tup Island or Poda Island depending on sea conditions',
  ],
  warning:
    'The itinerary may change depending on weather, tides, and sea conditions.',
};

const existing = Array.isArray(data.programs) ? data.programs : [];
data.programs = [...existing.filter((p) => p && p.id !== tour.id), tour];

if (data.homeSections?.boats) {
  data.homeSections.boats = {
    eyebrow: 'One-day trips',
    title: 'Krabi day trips by boat',
    lead:
      'Phi Phi, Maya Bay, Hong Island, and sunset tours — from 1,550 THB. Premium Maya Bay + sunset from 3,500 THB.',
  };
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log(data.programs.map((p) => `${p.id} @${p.basePrice}`).join('\n'));
