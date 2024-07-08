import HomeAbout from "@/components/home/HomeAbout";
import HomeBanner from "@/components/home/HomeBanner";
import HomeLiveTender from "@/components/home/HomeLiveTender";
import HomeServices from "@/components/home/HomeServices";
import HomeTestimonialsClients from "@/components/home/HomeTestimonialsClients";
import HomeWhyUs from "@/components/home/HomeWhyUs";
import NewsScroll from "@/components/NewsScroll";

export async function generateMetadata({ params }) {
  return {
    title: `Latest Government Tender Detail | Online eTender, Eprocurement, Bids | Govt ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Looking for government tenders? Discover a reliable source to find all published tender details on tender 18. With Tender's 24/7 support system, get access to the latest government tenders, online tender information, and stay updated with local tender news. Explore government tenders today.",
  };
}

const Home = () => {
  return (
    <>
      <HomeBanner />
      <HomeServices />
      <HomeLiveTender />
      <HomeAbout />
      <NewsScroll />
      <HomeWhyUs />
      <HomeTestimonialsClients />
    </>
  );
};

export default Home;
