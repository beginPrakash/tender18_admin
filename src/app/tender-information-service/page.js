import TenderInformtaionServicesBanner from "@/components/tender-information-service/TenderInformtaionServicesBanner";
import ListTendersInfo from "@/components/live-tenders/ListTendersInfo";
import FeaturesTendersInfo from "@/components/tender-information-service/FeaturesTendersInfo";
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
      <TenderInformtaionServicesBanner
        img="/images/tender-information-banner.webp"
        alt="Tender Information Service"
        title="Latest Tender Information Service - 2024"
        desc="First, Let's Understand A Term 'Tender'. Everyday There Are Lots Of Work Or Contract Published By Governing Agencies, Private Companies Public Sector Units Etc. They Are Publishing Every Work As A Form Of Tender. Tender18 Gathering All This Type Of Tender Information From All Open Sources With Tender Documents On Daily Basis. We Provide All This Information To Our Registered Clients Regularly Via E-Mail As Per Clients Specification And Requirements."
        innerimg="/images/tender-information-1.webp"
        innerImgAlt="Tender Information"
      />
      <ListTendersInfo />
      <FeaturesTendersInfo />
      <TenderInfoForm
        desc1="We Tender18 Always Understand Important Of Time And Money. Hence We Always Used Latest Technology Hardwares And Softwares That Give Us Exact Result As Every Our Customer Wants."
        desc2="With The Help Of This Technology We Gather Tender Information From Differrent Open Source Platforms And Sort It By Client Wise. As A Result Of This Technology We Provide All Sufficient Information At Right Time."
      />
    </>
  );
};

export default TenderInformtaionServices;
