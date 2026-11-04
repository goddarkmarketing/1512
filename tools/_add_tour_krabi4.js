const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const gallery = Array.from({ length: 7 }, (_, i) => `assets/uploads/tours/krabi-4islands/img-${i}.jpg`);

const tour = {
  id: 'krabi-4islands-speedboat',
  name: 'Krabi 4 Islands Tour by Speedboat',
  image: gallery[0],
  route: 'Phra Nang Cave Beach · Thale Waek · Chicken Island · Poda Island',
  stops: [
    'Phra Nang Cave Beach',
    'Thale Waek (Separated Sea)',
    'Chicken Island',
    'Poda Island',
  ],
  duration: 'From 08:00–09:00 hotel pickup',
  basePrice: 1350,
  childPrice: 1250,
  boats: ['speedboat'],
  desc:
    'Explore crystal-clear waters, white sandy beaches, and Krabi’s iconic limestone islands by speedboat — Phra Nang Cave Beach, Thale Waek (Separated Sea), Chicken Island, and Poda Island.',
  ribbon: 'From 1,350 ฿',
  rating: 4.8,
  reviewCount: '1.1k',
  oldPrice: 0,
  stars: 5,
  packageCode: 'K4-SB01',
  gallery,
  galleryCaption:
    'Phra Nang Cave Beach · Separated Sea · Chicken Island · Poda Island',
  licenseNo: '',
  itinerary: [
    {
      time: '08:00',
      title: 'Hotel pick-up & check-in',
      text:
        'Pick-up from your hotel (approximately 08:00–09:00 AM) and transfer to the pier for check-in. Board the speedboat and depart for the 4 Islands Tour',
    },
    {
      time: '09:30',
      title: 'Phra Nang Cave Beach',
      text:
        'Discover a beautiful beach surrounded by towering limestone cliffs. Visit the cave shrine, relax on the sand, and capture stunning coastal views',
    },
    {
      time: '11:00',
      title: 'Thale Waek — The Separated Sea',
      text:
        'At low tide, a white sandbar connects Tup Island, Mor Island, and Chicken Island. Walk between the islands and enjoy this remarkable natural attraction, depending on sea levels',
    },
    {
      time: '12:30',
      title: 'Chicken Island',
      text:
        'Admire the famous chicken-shaped limestone rock formation. Enjoy snorkeling in shallow waters and spotting colorful marine life',
    },
    {
      time: '14:00',
      title: 'Poda Island',
      text:
        'Relax on a white sandy beach, swim in clear water, and take photos with the striking Koh Tang Ming rock formation in the background',
    },
  ],
  season: 'Speedboat · Hotel pick-up approx. 08:00–09:00 AM · Krabi 4 Islands',
  inclusions: [
    'Hotel transfers',
    'Fruit',
    'Soft drinks',
    'Drinking water',
    'Tour guide',
    'Insurance',
    'Snorkeling equipment',
    'Life jacket',
    'Lunch',
  ],
  highlights: [
    'Phra Nang Cave Beach & shrine',
    'Thale Waek sandbar (tide dependent)',
    'Chicken Island snorkeling',
    'Poda Island beach & Koh Tang Ming views',
  ],
  experiences: [
    'Visit Phra Nang Cave Beach',
    'Walk the Separated Sea sandbar when tides allow',
    'Snorkel at Chicken Island',
    'Relax and swim at Poda Island',
  ],
  priceMeetingNote: 'Includes hotel transfers · Speedboat 4 Islands Tour',
  priceNotes: [
    'Adult: 1,350 THB/person',
    'Child (4–11 years): 1,250 THB/person',
    'Children under 3 years: Free',
    'National park entrance fees are not included',
    'Thale Waek (Separated Sea) depends on sea levels / low tide',
  ],
  warning:
    'The itinerary may change depending on weather conditions and sea levels.',
};

const existing = Array.isArray(data.programs) ? data.programs : [];
data.programs = [...existing.filter((p) => p && p.id !== tour.id), tour];

if (data.homeSections?.boats) {
  data.homeSections.boats = {
    eyebrow: 'One-day trips',
    title: 'Krabi day trips by boat',
    lead:
      'Krabi 4 Islands from 1,350 THB, Hong & Paradise from 1,550 THB, plus Phi Phi and sunset tours.',
  };
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log(data.programs.map((p) => `${p.id} @${p.basePrice}`).join('\n'));
