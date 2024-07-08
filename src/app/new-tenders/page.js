import NewTendersFlex from "@/components/new-tenders/NewTendersFlex";

export async function generateMetadata({ params }) {
  return {
    title: `Latest Indian Government Tenders, And GeM Portal - ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Get the latest updates on Indian tenders and projects from all india tender. Register now for FREE lifetime access to Indian tenders onli ne and effortlessly download Indian tender details from multiple Indian states and cities.",
  };
}

const NewTenders = () => {
  return (
    <>
      {/* <TendersBanner 
            imgsrc = {TenderBannerImg}
            alt = 'LiveTenderBanner'
            heading = 'New Tenders'
        /> */}

      <NewTendersFlex />
    </>
  );
};

export default NewTenders;
