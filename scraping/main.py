import downloader
import reformatter
import inserter
from sys import argv

course_ids = {
    'Physics': 'PL8dPuuaLjXtN0ge7yDk_UA0ldZJdhwkoV'
}
if len(argv) < 2 or argv[1] not in course_ids:
    print('Please provide a valid course name')
    exit(1)

course = argv[1]
course_id = course_ids[course]

downloader.download_course(course_id)
input('Please rename any necessary files, then hit enter')
reformatter.format_course(course)
inserter.generate_sql(course)
