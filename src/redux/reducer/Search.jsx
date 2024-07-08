import { createSlice } from "@reduxjs/toolkit";

let initialState = {
  keyword: "",
};

export const TendersearchSlice = createSlice({
  name: "tendersearch",
  initialState,
  reducers: {
    searchkeyword: (state, action) => {
      return { ...state, keyword: action.payload };
    },
  },
});

// Action creators are generated for each case reducer function
export const { searchkeyword } = TendersearchSlice.actions;

export default TendersearchSlice.reducer;
