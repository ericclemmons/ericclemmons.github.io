
gulp.task('jsrc', function() {
  return gulp
    .src([__filename, 'app/**/*.js'])
    .pipe($.plumber(function(error) {
      error.message = error.message
        .split(/\n\n/)
        .filter(function(message) {
          if (
            message.match('Unexpected token *') ||
            message.match('Unexpected reserved word') ||
            message.match('Invalid left-hand side in assignment')
          ) {
            return false;
          }

          return true;
        })
        .join('\n\n')
      ;

      if (!error.message) {
        this.emit('end');
      }
    }))
    .pipe($.jscs())
  ;
});
