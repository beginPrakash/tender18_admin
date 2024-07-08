import TenderInformtaionServicesBanner from "@/components/tender-information-service/TenderInformtaionServicesBanner";
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
      <TenderInformtaionServicesBanner
        img="/images/tender-biding-banner-img.webp"
        alt="Tender Bidding Support"
        title="Get Tender Bidding Support Services - Tender Filling From Expert Tender Consultancy"
        desc="After Getting Exact Tender Information From Trnder18 Next Step Is To Participate In Tenders. We Are Suggest Tenders To Our Client As Per Clients Business Profile There Are Various Steps To Participate In Tender. Our Technical Expert Team Are Alwyas Ready Fullfill All That Steps On Behalf Of Client. In This Service We Cover Browser Compatibility Settings, Vendor Registration In Perticular Department At Where Tender Is Published, Document Preparation As Per Tender Requirements, Document Uploading, Price Bidding, Tender Result Updation And After Result Support Like Purchase Order Or EMD Return Follow Up."
        innerimg="/images/tender-bidding-support-img1.webp"
        innerImgAlt="Tender Bidding Support Services"
      />
      <TendersBidingInfo />
      <TenderInfoForm desc1="Tender18 Have Experienced Technical Team Who Are Ready To New Challenges Everyday. Our Technical Team Are Able To Work With Any Government Or Private Departments. Also Our Staff Are Able To Give Proper Guidelines As Per Customers Business Profile To Grow Business Of Every Customer." />
    </>
  );
};

export default TenderBidingSupport;
