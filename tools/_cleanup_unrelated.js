const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

// Keep only today's package IDs + car rental
const keepProgramIds = new Set([
  'pp-early-bird-4islands',
  'pp-maya-bamboo-day',
  'hong-kayak-sunset',
  'hong-paradise-speedboat',
  'pp-islands-sunset',
  'pp-maya-sunset-4islands',
  'krabi-4islands-speedboat',
]);

data.programs = (data.programs || []).filter((p) => keepProgramIds.has(p.id));
data.packages2d1n = [];
data.packages3d2n = [];
data.packages4d3n = [];

// Remove old Trang charter / social / editorial content
data.boats = [];
data.options = [];
data.boatBooking = {
  insurancePerPerson: 0,
  routes: [],
  peopleTiers: [],
  charterPrices: {},
  addons: [],
  profiles: [],
};
data.articles = [];
data.reviews = [];
data.videos = [];
data.heroSlides = [];
data.homeDeals = {};

// Services relevant to today's offer
data.services = [
  {
    icon: 'speedboat',
    title: 'Tour packages',
    href: 'programs.html',
  },
  {
    icon: 'car',
    title: 'Car rental',
    href: 'cars.html',
  },
  {
    icon: 'line',
    title: 'Book on LINE',
    href: data.site?.lineUrl || 'https://line.me/R/ti/p/%40ndpoolvilla',
    external: true,
  },
];

// Navigation — only related pages
data.navItems = [
  { label: 'Home', href: 'index.html' },
  { label: 'Tour packages', href: 'programs.html' },
  { label: 'Car rental', href: 'cars.html' },
  { label: 'About us', href: 'about.html' },
  { label: 'Contact', href: 'contact.html' },
];

// Why us / steps — Suwan Travel Krabi
data.whyUs = [
  {
    icon: 'starOutline',
    title: 'Local Krabi team',
    desc: 'Based in Ao Nang, Krabi — easy to reach by phone, WhatsApp, or LINE.',
  },
  {
    icon: 'shield',
    title: 'Clear inclusions',
    desc: 'Transfers, guide, snorkeling gear, meals, and insurance listed on every tour.',
  },
  {
    icon: 'compass',
    title: 'Popular routes',
    desc: 'Phi Phi, Maya Bay, Hong Island, Krabi 4 Islands, and sunset / plankton trips.',
  },
  {
    icon: 'car',
    title: 'Car rental too',
    desc: 'Daily automatic cars in Krabi from 900 THB, with airport return available.',
  },
];

data.steps = [
  {
    n: 1,
    icon: 'search',
    title: 'Choose a package',
    desc: 'Pick a day trip or car rental that fits your date and group size.',
  },
  {
    n: 2,
    icon: 'calendar',
    title: 'Check the details',
    desc: 'Review itinerary, prices, inclusions, and park fees on the package page.',
  },
  {
    n: 3,
    icon: 'line',
    title: 'Message us',
    desc: 'Contact LINE @ndpoolvilla or WhatsApp to confirm availability.',
  },
  {
    n: 4,
    icon: 'check',
    title: 'Confirm & go',
    desc: 'Receive pickup time and meeting details before your trip.',
  },
];

data.about = {
  hero: {
    crumb: 'About us',
    title: 'Suwan Travel — Ao Nang, Krabi',
    lead: 'Day trips around Phi Phi, Hong Island, and Krabi’s 4 Islands, plus local car rental.',
    heroAlt: 'Ao Nang, Krabi sea tours',
  },
  story: {
    eyebrow: 'Our story',
    title: 'Travel experiences from Ao Nang',
    paragraphs: [
      'Suwan Travel and Tour operates from Ao Nang, Krabi. We arrange speedboat and longtail day trips to Phi Phi, Maya Bay, Hong Island, and the Krabi 4 Islands, plus daily car rental.',
      'Message us on LINE @ndpoolvilla or WhatsApp to check availability, pickup times, and current conditions.',
    ],
    ctaPrimary: { href: 'programs.html', label: 'View tour packages' },
    ctaSecondary: { href: 'cars.html', label: 'View car rental' },
  },
  whySection: {
    eyebrow: 'Why book with us',
    title: 'Simple booking, clear trip details',
  },
  trust: [
    { value: String((data.programs || []).length), label: 'Day-trip packages' },
    { value: String((data.carRental?.vehicles || []).length), label: 'Rental cars' },
    { value: 'LINE', label: '@ndpoolvilla' },
    { value: 'Ao Nang', label: 'Krabi base' },
  ],
  office: {
    eyebrow: 'Visit us',
    title: 'Ao Nang, Mueang Krabi',
    infoTitle: 'Contact channels',
    infoText:
      '1267 Moo 2, Ao Nang, Mueang Krabi, Krabi 81180. Call 093-7414111 / 080-6323483, WhatsApp +66937414111, or LINE @ndpoolvilla.',
  },
  cta: {
    title: 'Ready to plan your Krabi trip?',
    lead: 'Ask about tours or car rental on LINE @ndpoolvilla.',
    lineLabel: 'Chat on LINE',
    bookingLabel: 'Browse packages',
  },
};

data.homeSections = {
  ...(data.homeSections || {}),
  hero: {
    title: 'Krabi & Phi Phi packages',
    lead: 'Day trips by speedboat and longtail, plus daily car rental in Ao Nang, Krabi.',
  },
  boats: {
    eyebrow: 'One-day trips',
    title: 'Krabi day trips by boat',
    lead: 'Krabi 4 Islands, Hong Island, Phi Phi & Maya Bay, and sunset tours — from 1,350 THB.',
  },
  programs: {
    eyebrow: 'Overnight stays',
    title: 'Overnight packages',
    lead: '',
  },
  packages3d2n: { eyebrow: '', title: '', lead: '' },
  packages4d3n: { eyebrow: '', title: '', lead: '' },
  booking: {
    eyebrow: 'Easy booking',
    title: 'Choose a package and message us on LINE',
    lead: 'Check prices and itinerary online, then confirm on LINE @ndpoolvilla or WhatsApp.',
  },
  reviews: {
    eyebrow: '',
    title: '',
    lead: '',
  },
  videos: {
    eyebrow: '',
    title: '',
    lead: '',
    tiktokHandle: '',
  },
  office: {
    eyebrow: 'A real, visitable office',
    title: 'Visit us in Ao Nang, Krabi',
    infoTitle: 'Confidence on every trip',
    infoLead:
      'Our office is in Ao Nang, Mueang Krabi. Call, WhatsApp, or LINE before you book.',
  },
  cta: {
    eyebrow: 'Ready to go?',
    title: 'Book tours or car rental via LINE',
    lead: 'Our team replies on LINE @ndpoolvilla and WhatsApp.',
  },
};

// SEO — keep relevant pages only / refresh
data.seo = {
  index: {
    title: 'Suwan Travel — Krabi & Phi Phi Day Trips + Car Rental',
    description:
      'Krabi day trips to Phi Phi, Maya Bay, Hong Island, and 4 Islands, plus car rental in Ao Nang. Book via LINE @ndpoolvilla.',
    ogTitle: 'Suwan Travel — Krabi day trips & car rental',
    ogDescription: 'Phi Phi, Hong Island, Krabi 4 Islands, sunset tours, and daily car rental.',
    ogImage: data.images?.heroHome || '',
  },
  programs: {
    title: 'Tour Packages — Phi Phi, Hong Island, Krabi 4 Islands | Suwan Travel',
    description: 'Browse Krabi and Phi Phi day-trip packages by speedboat and longtail.',
    ogTitle: 'Tour packages | Suwan Travel',
    ogDescription: 'Day trips from Ao Nang, Krabi.',
    ogImage: data.images?.heroPrograms || '',
  },
  cars: data.seo?.cars || {
    title: 'Car Rental in Krabi | Suwan Travel',
    description: 'Daily car rental in Krabi from 900 THB.',
    ogTitle: 'Car Rental | Suwan Travel',
    ogDescription: 'Automatic cars in Krabi.',
    ogImage: '',
  },
  about: {
    title: 'About Suwan Travel — Ao Nang, Krabi',
    description: 'Suwan Travel and Tour in Ao Nang, Krabi — day trips and car rental.',
    ogTitle: 'About Suwan Travel',
    ogDescription: 'Ao Nang, Krabi tour operator.',
    ogImage: data.images?.heroAbout || '',
  },
  contact: {
    title: 'Contact Suwan Travel — LINE @ndpoolvilla',
    description: 'Call 093-7414111, 080-6323483, WhatsApp +66937414111, LINE @ndpoolvilla.',
    ogTitle: 'Contact Suwan Travel',
    ogDescription: 'Ao Nang, Krabi contact channels.',
    ogImage: data.images?.heroContact || '',
  },
};

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
  note: 'Cleaned to packages and details provided for Suwan Travel Krabi',
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log('programs', data.programs.length);
console.log('cleared articles/reviews/videos/boats', data.articles.length, data.reviews.length, data.videos.length, data.boats.length);
console.log('nav', data.navItems.map((n) => n.label).join(' | '));
