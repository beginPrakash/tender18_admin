// import TendersBanner from './TendersBanner';
import ArchiveTendersFlex from "@/components/archive-tenders/ArchiveTendersFlex";

export async function generateMetadata({ params }) {
  return {
    title: `Get Archive Tenders | Latest Tenders in Archive - ${process.env.NEXT_PUBLIC_SITE_NAME}`,
    description:
      "Looking for the latest tenders in archive? Discover a reliable and extensive source of tender information with get archive tenders. Stay updated with the latest opportunities for your business growth. Start exploring n ow with tender18",
  };
}

const ArchiveTenders = () => {
  return (
    <>
      {/* <TendersBanner 
            imgsrc = {TenderBannerImg}
            alt = 'LiveTenderBanner'
            heading = 'Archive Tenders'
        /> */}

      <ArchiveTendersFlex />
    </>
  );
};

export default ArchiveTenders;
