"use client";
import { useEffect, useState } from "react";
import axios from "axios";
import Link from "next/link";

const HomeServices = () => {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/service-section.php?endpoint=getServiceData"
        );
        setData(response.data.data.main);
        setDataDetails(Object.values(response.data.data.details));
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  // if (loading) {
  //   return <div className="loader">
  //     Loading....
  //   </div>;
  // }
  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div className="home-services-main">
          <div className="container-main">
            <div className="services-title text-center">
              <h2>{data.title}</h2>
              <p>{data.description}</p>
            </div>

            <div className="home-services-flex">
              {dataDetails.map((servicedata, index) => {
                return (
                  <div className="home-services-block" key={index}>
                    <div className="home-services-block-inner">
                      <div className="home-services-logo">
                        <img src={servicedata.image} alt={servicedata.alt} />
                      </div>

                      <div className="home-service-info">
                        <h4>{servicedata.title}</h4>
                        <p>{servicedata.description}</p>
                        <Link href={servicedata.link}>Read More</Link>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default HomeServices;
