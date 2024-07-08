"use client";
import { useEffect, useState } from "react";
import axios from "axios";
import Link from "next/link";

const NewsScroll = () => {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);
  const [city, setCity] = useState([]);
  const [cityDetails, setCityDeatils] = useState([]);
  const [keyword, setkeyword] = useState([]);
  const [keywordDetails, setkeywordDeatils] = useState([]);
  const [serviceDetails, setserviceDeatils] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/gem-services-section.php?endpoint=getGeMServicesData"
        );
        setData(response.data.data.main);
        setCity(response.data.data.main);
        setkeyword(response.data.data.main);
        setDataDetails(Object.values(response.data.data.state));
        setCityDeatils(Object.values(response.data.data.city));
        setkeywordDeatils(Object.values(response.data.data.keyword));
        setserviceDeatils(Object.values(response.data.data.services));
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
  //   </div>
  // }
  return (
    <>
      <div className="home-news-scroll-main">
        <div className="container-main">
          <div className="home-news-scroll-flex">
            <div className="news-scroll-left">
              <div className="news-scroll-left-block">
                <h6>{data.state_title}</h6>
                <div className="news-scroll-left-links">
                  <ul>
                    {dataDetails.map((headingdata, index) => {
                      return (
                        <li key={index}>
                          <Link href={headingdata.link ?? ""}>
                            {headingdata.title}
                          </Link>
                        </li>
                      );
                    })}
                  </ul>
                </div>
              </div>

              <div className="news-scroll-left-block">
                <h6>{city.city_title}</h6>
                <div className="news-scroll-left-links">
                  <ul>
                    {cityDetails.map((headingdata, index) => {
                      return (
                        <li key={index}>
                          <Link href={headingdata.link ?? ""}>
                            {headingdata.title}
                          </Link>
                        </li>
                      );
                    })}
                  </ul>
                </div>
              </div>

              <div className="news-scroll-left-block">
                <h6>{keyword.keyword_title}</h6>
                <div className="news-scroll-left-links">
                  <ul>
                    {keywordDetails.map((headingdata, index) => {
                      return (
                        <li key={index}>
                          <Link href={headingdata.link ?? ""}>
                            {headingdata.title}
                          </Link>
                        </li>
                      );
                    })}
                  </ul>
                </div>
              </div>
            </div>

            <div className="news-scroll-right">
              <div className="news-scroll-right-main">
                <div className="news-scroll-right-title">
                  <h4>{data.gem_title}</h4>
                </div>

                <div className="news-scroll-right-links">
                  <div className="filters-checkbox">
                    {serviceDetails.map((scrolllinks, index) => {
                      return (
                        <label key={index} htmlFor={scrolllinks.for}>
                          {scrolllinks.title}
                        </label>
                      );
                    })}
                  </div>
                </div>

                <div className="views-more-news-btn">
                  <Link href={data.gem_button_link ?? ""}>
                    {data.gem_button_text}
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default NewsScroll;
