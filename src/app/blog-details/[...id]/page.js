"use client";
import React, { useEffect, useState, useRef } from "react";
import axios from "axios";
import { useParams } from "next/navigation";

const AllTendersDetails = () => {

  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(false);
  let { id } = useParams();


  useEffect(() => {

    const fetchData = async () => {
        setLoading(true);
      if (typeof window !== "undefined") {
         
          let user1 = {};
          user1 = {
            id: id[0],
            endpoint: "getBlogDetailData",
          };
          const response1 = await axios.post(
            `${process.env.NEXT_PUBLIC_API_BASEURL}` + "/blogs/detail.php",
            user1
          );
          setData(response1.data.data.main);
          setLoading(false);
      }
    };
    
    fetchData();
  }, []);


  return (
    <>
     {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
      <div className="tenders-details-page-main">
        <div className="container-main">
            <div className="tenders-info-services-width">
                <div className="tenders-info-services-flex lit-tender-info-flex">
                    <div className="tenders-info-services-left">
                        <h4>{data.title}</h4>
                        <p>{data.description}</p>
                        
                    </div> 
                    <div className="blog-info-services-right">
                        <img src={data.blog_image} alt="Blogs"></img>
                    </div>  
                </div>
            </div>            
        </div>
      </div>
      )}
    </>
  );
};

export default AllTendersDetails;

