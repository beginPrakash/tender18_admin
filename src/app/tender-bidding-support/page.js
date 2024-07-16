import BannerInfo from "@/components/tender-bidding-support/BannerInfo";
import TendersBidingInfo from "@/components/tender-bidding-support/TendersBidingInfo";
import TenderInfoForm from "@/components/tender-info/TenderInfoForm";

export async function generateMetadata({ params }) {
  return {
    title: `Tender Bidding Support | Tender Filling Services - ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Looking for professional tender bidding support and tender filling services? Discover how tender18 can streamline your bidding process, boost success rates, and save you time and effort.",
  };
}

const TenderBidingSupport = () => {
  return (
    <>
      <BannerInfo/>
      <TendersBidingInfo />
      <TenderInfoForm desc1="Tender18 Have Experienced Technical Team Who Are Ready To New Challenges Everyday. Our Technical Team Are Able To Work With Any Government Or Private Departments. Also Our Staff Are Able To Give Proper Guidelines As Per Customers Business Profile To Grow Business Of Every Customer." />
    </>
  );
};

export default TenderBidingSupport;
