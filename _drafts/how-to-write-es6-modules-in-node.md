`yield` is one of the best reasons to start using ES6 today.



class Address {
  constructor: function(props) {
    this.props = props;
  }

  validate: function* () {
    asyncAssert(this.props)
  }

  normalize: function* () {
    return yield asyncNormalize(this.props);
  }
}

yield address({ ... })
