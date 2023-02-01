import yt_dlp


# Convert command line arguments to Python API arguments
# https://github.com/yt-dlp/yt-dlp/issues/5859
def cli_to_api(*opts):
    default = yt_dlp.parse_options([]).ydl_opts
    diff = {k: v for k, v in yt_dlp.parse_options(opts).ydl_opts.items() if default[k] != v}
    if 'postprocessors' in diff:
        diff['postprocessors'] = [pp for pp in diff['postprocessors'] if pp not in default['postprocessors']]
    return diff


print(cli_to_api('--write-comments', '--no-download',
                 '--extractor-args', 'youtube:max-comments=50,20,50,5;comment_sort=top',
                 '-o', 'abc',
                 '--quiet'))
