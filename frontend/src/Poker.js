import React, { Component } from "react";
import PokerCard from "./PokerCard";

export default class Poker extends Component {
  constructor(props) {
    super(props);
    this.state = { poker: {} };
  }

  componentDidMount() {
    fetch(`http://localhost`)
      .then(res => res.json())
      .then(res => this.setState({ poker: res }));
  }

  render() {
    const { poker } = this.state;
    const { players, winner } = poker;
    return (
      <div>
        <>
          {players &&
            players.map(player => (
              <div style={{ display: "flex" }}>
                {player.hand &&
                  player.hand.cards.map(card => (
                    <PokerCard cardName={`${card.value}${card.suit}`} />
                  ))}
                <h1>{player.evaluate.hand_name}</h1>
                <br />
                <h1>
                  Carta mais alta:{" "}
                  {`${player.evaluate.high_card.value}${player.evaluate.high_card.suit}`}
                </h1>
              </div>
            ))}
          {winner && <h1>Vencedor: {`${winner.name}`}</h1>}
        </>
      </div>
    );
  }
}
