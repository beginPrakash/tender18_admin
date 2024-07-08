"use client";
import React, { useState, useEffect } from "react";
import axios from "axios";
import { useDispatch } from "react-redux";
import { searchkeyword } from "@/redux/reducer/Search";
// import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
// import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';

const HomeBanner = () => {
  const [data, setData] = useState([]);
  const [dataDetails, setDataDetails] = useState([]);
  const [loading, setLoading] = useState(true);
const dispatch = useDispatch()

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/banner-section.php?endpoint=getBannerData"
        );
        setData(response.data.data.main);
        setDataDetails(Object.values(response.data.data.details));
      } catch (error) {
        console.error("Error fetching data:", error);
      } finally {
        setLoading(false);
      }
    };
      dispatch(searchkeyword(""));
      localStorage.removeItem("filterData");
    fetchData();
  }, []);

  // if (loading) {
  //   return <div className="loader">
  //   Loading....
  // </div>;
  // }

  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div
          className="home-banner-main"
          style={{ backgroundImage: `url(${data.image})` }}
        >
          <div className="container-main">
            <div className="home-banner-info">
              <h1>{data.title}</h1>
              <p>{data.description}</p>
            </div>
            <div className="banner-search">
              <form action="">
                <div className="banner-serch-flex">
                  <div className="banner-search-left">
                    <div className="banner-serach-left-main">
                      <div className="banner-search-box">
                        <i className="fa fa-solid fa-magnifying-glass"></i>
                        <input
                          type="text"
                          placeholder="What are you looking for?"
                          name="search"
                        />
                      </div>
                    </div>

                    <div className="banner-search-submit">
                      <input type="submit" value="Search" />
                    </div>
                  </div>
                  <div className="banner-search-right">
                    <div className="banner-serach-select">
                      <select name="">
                        <option value="">Tenders</option>
                      </select>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div className="banner-stats">
              <div className="banner-stas-flex">
                {dataDetails.map((stasdata, index) => {
                  return (
                    <div className="banner-stats-block" key={index}>
                      <div className="banner-stats-info">
                        <h5>{stasdata.sub_title}</h5>
                        <h6>{stasdata.title}</h6>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default HomeBanner;
