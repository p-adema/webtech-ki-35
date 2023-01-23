import downloader
import reformatter
import inserter
from sys import argv

course_ids = {
    'Physics': 'PL8dPuuaLjXtN0ge7yDk_UA0ldZJdhwkoV'
}
if argv[1] not in course_ids:
    print('Please add this course ID to the list')
    exit(1)

course = argv[1]
course_id = course_ids[course]

# downloader.download_course(course_id)
reformatter.format_course(course)
inserter.generate_sql(course)
