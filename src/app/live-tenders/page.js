// import TendersBanner from './TendersBanner';
import TendersFlex from "@/components/live-tenders/LiveTendersFlex";

export async function generateMetadata({ params }) {
  return {
    title: `Latest Live Tenders | Get eTenders Live Tender - ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Find the best online live tenders and access public tenders for a Free Consultant service at tender18. Streamline your bidding process and secure lucrative contracts. Don't miss out, start exploring today",
  };
}

const LIveTenders = () => {
  return (
    <>
      {/* <TendersBanner 
            imgsrc = {TenderBannerImg}
            alt = 'LiveTenderBanner'
            heading = 'Live Tenders'
        /> */}
      <TendersFlex />
    </>
  );
};

export default LIveTenders;
