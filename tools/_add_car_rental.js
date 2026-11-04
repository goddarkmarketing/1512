const fs = require('fs');
const path = 'c:/xampp/htdocs/1512/data/site.json';
const data = JSON.parse(fs.readFileSync(path, 'utf8'));

const img = (i) => `assets/uploads/tours/car-rental/img-${i}.jpg`;

data.carRental = {
  title: 'Car Rental',
  lead: 'Daily self-drive car rental in Krabi. All vehicles have automatic transmission. Airport return available.',
  airportReturnFee: 300,
  deposit: {
    cash: 3000,
    cardOrCashHold: 5000,
    note: 'Cash deposit 3,000 THB, or cash/credit card hold 5,000 THB depending on rental terms.',
  },
  conditions: [
    'Renters must present a valid ID card or passport and a valid driving licence.',
    'Renters must pay the required security deposit.',
    'Insurance is included. The renter is responsible for accident damage up to 10,000 THB; amounts exceeding this may not be covered — please confirm with the rental provider.',
    'The vehicle must be returned with the same fuel level as at pickup.',
    'Vehicles may be used within Krabi Province only. Travel to Phang Nga or Phuket is not permitted.',
  ],
  notes: [
    'Return at Krabi Airport: additional 300 THB.',
    'The Toyota Yaris ATIV is listed at 1,100 THB beside the vehicle photo, but 1,000 THB in a lower brochure price table — please confirm the correct rate before booking.',
  ],
  vehicles: [
    {
      id: 'nissan-march',
      name: 'Nissan March',
      seats: 5,
      transmission: 'Automatic',
      pricePerDay: 900,
      image: img(0),
    },
    {
      id: 'suzuki-swift',
      name: 'Suzuki Swift',
      seats: 5,
      transmission: 'Automatic',
      pricePerDay: 900,
      image: img(1),
    },
    {
      id: 'toyota-yaris-hatchback',
      name: 'Toyota Yaris Hatchback',
      seats: 5,
      transmission: 'Automatic',
      pricePerDay: 1000,
      image: img(2),
    },
    {
      id: 'toyota-yaris-ativ',
      name: 'Toyota Yaris ATIV',
      seats: 5,
      transmission: 'Automatic',
      pricePerDay: 1100,
      image: img(3),
      note: 'Brochure also lists 1,000 THB in one table — confirm before booking',
    },
    {
      id: 'all-new-toyota-yaris-ativ',
      name: 'All New Toyota Yaris ATIV',
      seats: 5,
      transmission: 'Automatic',
      pricePerDay: 1100,
      image: img(4),
    },
    {
      id: 'toyota-vios',
      name: 'Toyota Vios',
      seats: 5,
      transmission: 'Automatic',
      pricePerDay: 1100,
      image: img(5),
    },
    {
      id: 'honda-jazz',
      name: 'Honda Jazz',
      seats: 5,
      transmission: 'Automatic',
      pricePerDay: 1100,
      image: img(6),
    },
    {
      id: 'toyota-veloz',
      name: 'Toyota Veloz',
      seats: 7,
      transmission: 'Automatic',
      pricePerDay: 1500,
      image: img(7),
      tag: '7 Seats',
    },
    {
      id: 'mitsubishi-xpander',
      name: 'Mitsubishi Xpander',
      seats: 7,
      transmission: 'Automatic',
      pricePerDay: 1500,
      image: img(8),
      tag: '7 Seats',
    },
  ],
};

// Nav
const nav = Array.isArray(data.navItems) ? data.navItems : [];
if (!nav.some((n) => n && n.href === 'cars.html')) {
  const programsIdx = nav.findIndex((n) => n && /programs\.html/i.test(n.href || ''));
  const item = { label: 'Car rental', href: 'cars.html' };
  if (programsIdx >= 0) nav.splice(programsIdx + 1, 0, item);
  else nav.push(item);
  data.navItems = nav;
}

// Service pill → cars page
if (Array.isArray(data.services)) {
  data.services = data.services.map((s) => {
    if (s && /car rental/i.test(String(s.title || ''))) {
      return { ...s, href: 'cars.html', external: false };
    }
    return s;
  });
}

if (!data.seo) data.seo = {};
data.seo.cars = {
  title: 'Car Rental in Krabi — Daily Rates from 900 THB | Suwan Travel',
  description:
    'Rent a car in Krabi from 900 THB/day. Nissan March, Suzuki Swift, Toyota Yaris, Vios, Honda Jazz, Veloz, Xpander. Automatic transmission. Airport return available.',
  ogTitle: 'Car Rental in Krabi | Suwan Travel',
  ogDescription: 'Daily self-drive car rental from 900 THB. All automatic. Krabi Airport return +300 THB.',
  ogImage: img(0),
};

if (data.images && !data.images.heroCars) {
  data.images.heroCars = img(0);
}

data.meta = {
  ...(data.meta || {}),
  updated: new Date().toISOString(),
};

fs.writeFileSync(path, JSON.stringify(data, null, 4), 'utf8');
console.log('vehicles', data.carRental.vehicles.length);
console.log('nav', data.navItems.map((n) => n.label).join(' | '));
