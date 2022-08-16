// ECMAScript 5 polyfill for a better sorting method
Object.defineProperty(Array.prototype, 'stableSort', {
  configurable: true,
  writable: true,
  value: function stableSort (compareFunction) {
    'use strict'

    var length = this.length
    var entries = Array(length)
    var index

    // wrap values with initial indices
    for (index = 0; index < length; index++) {
      entries[index] = [index, this[index]]
    }

    // sort with fallback based on initial indices
    entries.sort(function (a, b) {
      var comparison = Number(this(a[1], b[1]))
      return comparison || a[0] - b[0]
    }.bind(compareFunction))

    // re-map original array to stable sorted values
    for (index = 0; index < length; index++) {
      this[index] = entries[index][1]
    }
    
    return this
  }
});