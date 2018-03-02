import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';

export default class Root extends Component {

    constructor(props) {
        super(props);
        this.state = {
            items: []
        };
    }

    componentDidMount() {
        axios.get('http://localhost:8000/api/list-all-broadband').then(data => {
            this.setState({items: data.data});
        });
    }

    render() {
        return (
            <div className="content">
                {this.state.items.map(item => {
                    return (
                        <div className="card" key={item.title}>
                            <div className="card-body">
                                {item.title} <br/>
                                <strong>R$ {item.price}</strong>
                            </div>
                        </div>
                    );
                })}
            </div>
        )
    }
}

if (document.getElementById('app')) {
    ReactDOM.render(<Root />, document.getElementById('app'))
}