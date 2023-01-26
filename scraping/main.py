import downloader
import reformatter
import inserter
from sys import argv

if len(argv) < 2:
    print('Please provide a course name')
    exit(1)

course = argv[1]

downloader.download_course(course)
input('Please rename any necessary files, then hit enter')
reformatter.format_course(course)
inserter.generate_sql(course)
