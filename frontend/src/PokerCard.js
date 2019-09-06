import React, { Component } from "react";

export default class PokerCard extends Component {
  render() {
    const { cardName } = this.props;
    return (
      <div>
        <img
          alt={cardName}
          style={{ width: 100, margin: 15 }}
          src={`./cards/${cardName}.svg`}
        />
      </div>
    );
  }
}
