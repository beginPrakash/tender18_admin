"use client";
import React, { useEffect } from "react";
import NewTendersList from "./NewTendersList";

const NewTendersFlex = () => {
  return (
    <>
      <div className="tenders-details-main">
        <div className="container-main">
          <div className="tenders-page-title">
            <h2>
              Latest Indian Government Tenders, And GeM Portal Tenders 2024
              Landing
            </h2>
          </div>
          <NewTendersList />
        </div>
      </div>
    </>
  );
};

export default NewTendersFlex;
