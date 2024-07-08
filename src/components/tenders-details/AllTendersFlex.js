"use client";
import React from "react";
import AllTendersList from "./AllTendersList";

const AllTendersFlex = () => {
  return (
    <>
      <div className="tenders-details-main">
        <div className="container-main">
          <div className="tenders-page-title">
            <h2>
              All Govt Tenders, Eprocurement Tenders, And GeM Portal Tenders
            </h2>
          </div>
          <AllTendersList />
        </div>
      </div>
    </>
  );
};

export default AllTendersFlex;
