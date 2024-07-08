"use client";
import React, { useEffect, useState } from "react";
import Accordion from "react-bootstrap/Accordion";
import axios from "axios";

const BlogList = () => {
  const [blogsData, setBlogsData] = useState([]);
  const [loading, setLoading] = useState(false);

  const getTenderBlogs = async () => {
    try {
      setLoading(true);
      const response = await axios.get(
        `${process.env.NEXT_PUBLIC_API_BASEURL}` +
          "blogs/blogs.php?endpoint=getBlogsData"
      );

      if (response.data.status == " success") {
        setBlogsData(response?.data?.data?.blogs)
        setLoading(false);
      }
    } catch (error) {
      setLoading(false);
    }
  };

  useEffect(() => {
    getTenderBlogs();
  }, []);

  return (
    <>
      {loading ? (
        <div className="loader">
          <img src="/images/Iphone-spinner-2.gif" alt="" />
        </div>
      ) : (
        <div className="faqs-main">
          <div className="container-main">
            <div className="register-form-title">
              <h2>Blogs</h2>
            </div>

            <div className="banner-stats">
              <div className="banner-stas-flex">
              {Object.values(blogsData)?.map((blogdata, index) => {
                  return (
                    <>
                    <div class="list-tender-info">
                        <div class="container-main">
                            <div class="tenders-info-services-width">
                                <div class="tenders-info-services-flex lit-tender-info-flex">
                                    <div class="tenders-info-services-left">
                                        <h4>{blogdata.title}</h4>
                                        <p>{blogdata.description.substring(0, 500)}</p>
                                        <a href={`/blog-details/${blogdata.blog_id}`}
                            target="_blank"
                          >Read More</a>
                                    </div> 
                                    <div class="blog-info-services-right">
                                        <img src={blogdata.blog_image} alt="Blogs"></img>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    </>
                    
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

export default BlogList;
