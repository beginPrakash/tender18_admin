"use client";
import { useEffect, useState } from "react";
import axios from "axios";
import Link from "next/link";

const HomeAbout = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_BASEURL}` +
            "homepage/about-us-section.php?endpoint=getAboutUsData"
        );
        setData(response.data.data.main);
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
        <div className="home-about-main">
          <div className="container-main">
            <div className="home-about-flex">
              {/* <div className="home-about-left">
                      <div className="home-about-form">
                          <form action="">
                              <input type="text" placeholder='Username'/>
                              <input type="password" placeholder='Password'/>
                              <input type="submit" value="Login"/>
                          </form>
                      </div>
                  </div> */}
              <div className="home-about-right">
                <h2>{data.title}</h2>
                <p dangerouslySetInnerHTML={{ __html: data.description }}></p>
                <Link href={data?.link ?? ""}>Read More</Link>
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default HomeAbout;
