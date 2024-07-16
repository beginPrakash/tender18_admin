import TenderInformtaionServicesBanner from "@/components/tender-information-service/TenderInformtaionServicesBanner";
import ListTendersInfo from "@/components/live-tenders/ListTendersInfo";
import FeaturesTendersInfo from "@/components/tender-information-service/FeaturesTendersInfo";
import BannerInfo from "@/components/tender-information-service/BannerInfo";
import TenderInfoForm from "@/components/tender-info/TenderInfoForm";

export async function generateMetadata({ params }) {
  return {
    title: `Tender Information Service| Free Tender Document Information ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Looking for reliable tender information service? We offer free tender document information that will help you stay ahead in your industry. Discover lucrative opportunities and make informed b usiness decisions with our comprehensive database. Start exploring today!",
  };
}

const TenderInformtaionServices = () => {
  return (
    <>
      <BannerInfo/>
      <ListTendersInfo />
      <FeaturesTendersInfo />
      <TenderInfoForm/>
    </>
  );
};

export default TenderInformtaionServices;
