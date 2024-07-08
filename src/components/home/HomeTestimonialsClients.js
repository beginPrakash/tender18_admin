"use client";
import { useEffect, useState } from "react";
import Slider from "react-slick";
import axios from "axios";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

const clients = {
  items: 6,
  margin: 40,
  responsiveClass: true,
  nav: true,
  dots: true,
  autoplay: true,
  smartSpeed: 1400,
  autoplaySpeed: 7000,
  autoplayTimeout: 7000,
  slidetransition: "linear",
  responsive: {
    0: {
      items: 2.5,
      nav: false,
      dots: false,
    },

    400: {
      items: 2.5,
    },
    600: {
      items: 3,
    },
    700: {
      items: 3,
    },
    991: {
      items: 5,
      nav: true,
      dots: true,
    },
    1000: {
      items: 5,
    },
    1367: {
      items: 6,
    },
  },
};

const settings = {
  dots: true,
  infinite: true,
  speed: 500,
  slidesToShow: 5,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 3000,
};

const settings1 = {
  infinite: true,
  speed: 500,
  slidesToShow: 1,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 2000,
  arrows: false,
  fade: true,
};

const HomeTestimonialsClients = () => {
  const [dataDetailsHome, setDataDetailsHome] = useState([]);
  console.log({ dataDetailsHome });
  const [testDetailsHome, setTestDetailsHome] = useState([]);
  const [loading, setLoading] = useState(true);

  const returnAltTag = (value) => {
    const data = [
      {
        image:
          "https://tender18.com/admin/uploads/images/Bharat_Heavy_Electricals_Limited-Logo.wine (1).png",
        tag: "BHEL Tenders",
      },
      {
        image:
          "https://tender18.com/admin/uploads/images/320px-NTPC_Logo.svg (1).png",
        tag: "NTPC Tenders",
      },
      {
        image: "https://tender18.com/admin/uploads/images/ONGC l.jpeg",
        tag: "ONGC Tenders",
      },
      {
        image: "https://tender18.com/admin/uploads/images/cpwd.jpeg",
        tag: "CPWD Tenders",
      },
      {
        image: "https://tender18.com/admin/uploads/images/bsf _ (1).png",
        tag: "BSF Tenders",
      },
      {
        image: "https://tender18.com/admin/uploads/images/SAIL (1).png",
        tag: "SAIL Tenders",
      },
      {
        image: "https://tender18.com/admin/uploads/images/HAL (1) (1).png",
        tag: "HAL Tenders",
      },
      {
        image: "https://tender18.com/admin/uploads/images/BSNL (1).png",
        tag: "BSNL Tenders",
      },
      {
        image: "https://tender18.com/admin/uploads/images/coal (1).png",
        tag: "COAL Tenders",
      },
    ];

    const returnAltTag = data?.find((d) => d?.image == value)?.tag || "";
    return returnAltTag;
  };

  useEffect(() => {
    const fetchData_new = async () => {
      try {
        const client_response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/partners-section.php?endpoint=getPartnersData"
        );
        const test_response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/testimonials-section.php?endpoint=getTestimonialsData"
        );
        //   console.log(Object.values(test_response.data.data.main));
        setDataDetailsHome(Object.values(client_response.data.data.main));
        setTestDetailsHome(Object.values(test_response.data.data.main));
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        // console.log(dataDetailsHome);
        // console.log(testDetailsHome);
        setLoading(false);
      }
    };
    //   setTimeout(() => {
    fetchData_new();
    //   }, 2000);
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
        <div className="home-clients-testimonials-main">
          <div className="container-main">
            <div className="home-clients-testimonials-flex">
              <div className="home-testimonials">
                <div className="testimonials-slider">
                  <div className="testimonial-slider-inner">
                    {testDetailsHome.length && (
                      <Slider {...settings1}>
                        {testDetailsHome.map((testdata, index) => {
                          return (
                            <div className="testimonials-block" key={index}>
                              <div className="testimonials-info">
                                <h4>{testdata.title}</h4>
                                <h3>{testdata.description}</h3>
                                <h6>{testdata.name}</h6>
                              </div>
                            </div>
                          );
                        })}
                      </Slider>
                    )}
                  </div>
                </div>
              </div>

              <div className="home-clients">
                <div className="clientes-slider">
                  {dataDetailsHome.length && (
                    <Slider {...settings}>
                      {dataDetailsHome.map((clientsdata, index) => {
                        return (
                          <div key={index}>
                            <img
                              style={{ width: "90%", height: 130 }}
                              src={clientsdata.image}
                              alt={returnAltTag(clientsdata?.image) || clientsdata?.image }
                            />
                          </div>
                        );
                      })}
                    </Slider>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default HomeTestimonialsClients;
