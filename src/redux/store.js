import { configureStore } from "@reduxjs/toolkit";
import TendersearchReducer from "./reducer/Search";

export const store = configureStore({
  reducer: {
    tendersearch: TendersearchReducer,
  },
});
