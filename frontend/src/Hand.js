import React, { Component } from "react";
import PokerCard from "./PokerCard";

export default class Hand extends Component {
  render() {
    const { hand } = this.props;
    return (
      <div style={{ display: "flex" }}>
        {hand &&
          hand.cards.map((card, index) => (
            <PokerCard cardName={`${card.value}${card.suit}`} key={index} />
          ))}
      </div>
    );
  }
}
