import Header from "@/components/Header";
import "../../public/css/index.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "../../public/css/font-awesome/6.4.2/css/all.min.css";
import Footer from "@/components/Footer";
import { Providers } from "@/redux/provider";
import Canonical from "@/components/common/Canonical";
import { GoogleAnalytics } from '@next/third-parties/google'

// export const metadata = {
//   title: "Tender18",
//   description: "Tender18",
// };

export default function RootLayout({ children }) {
  const jsonLd = {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    name: "Tender18 Infotech",
    image:
      "https://tender18.com/admin/uploads/images/desktop_logo_tender18.webp",
    "@id": "",
    url: "https://tender18.com/",
    telephone: "7069661818",
    priceRange: "00",
    address: {
      "@type": "PostalAddress",
      streetAddress:
        "401-402, 4th Floor, Shree Narnarayan Palace, NR. Kothawala Flat, Opp. Dadi Dining Hall, Paldi",
      addressLocality: "Ahmedabad",
      postalCode: "380006",
      addressCountry: "IN",
    },
    geo: {
      "@type": "GeoCoordinates",
      latitude: 23.022505,
      longitude: 72.571365,
    },
    openingHoursSpecification: {
      "@type": "OpeningHoursSpecification",
      dayOfWeek: [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
      ],
      opens: "00:00",
      closes: "23:59",
    },
    sameAs: [
      "https://www.facebook.com/tender18infotechofficial",
      "https://twitter.com/Tender18infotec",
      "https://www.instagram.com/tender18infotech/",
      "https://www.youtube.com/@tender184",
      "https://www.linkedin.com/company/tender18-infotech/",
      "https://tender18.com/",
    ],
  };

  const jsonLd1 = {
    "@context": "https://schema.org",
    "@type": "Organization",
    name: "Tender18 Infotech",
    alternateName: "Tender18",
    url: "https://tender18.com/",
    logo: "https://tender18.com/admin/uploads/images/desktop_logo_tender18.webp",
    sameAs: [
      "https://www.facebook.com/tender18infotechofficial",
      "https://twitter.com/Tender18infotec",
      "https://www.instagram.com/tender18infotech/",
      "https://www.youtube.com/@tender184",
      "https://www.linkedin.com/company/tender18-infotech/",
    ],
  };

  const jsonLd2 = {
    "@context": "http://schema.org",
    "@type": "Product",
    name: "Tender18 Infotech",
    aggregateRating: {
      "@type": "AggregateRating",
      ratingValue: "5",
      ratingCount: "41",
      reviewCount: "151",
    },
  };

  return (
    <html lang="en">
      <head>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
        ></script>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd1) }}
        ></script>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd2) }}
        ></script>
        <GoogleAnalytics gaId="G-8PRJ33VYJM" />
        <Canonical />
      </head>
      <body className="inner-page">
        <Providers>
          <Header />
          {children}
          <Footer />
        </Providers>
      </body>
    </html>
  );
}
