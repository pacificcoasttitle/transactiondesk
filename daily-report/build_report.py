import os, yaml
from datetime import datetime
from dotenv import load_dotenv
import pandas as pd
from sqlalchemy import create_engine, text
from jinja2 import Environment, FileSystemLoader
from transforms import TRANSFORMS

load_dotenv()
SQLALCHEMY_URL = os.getenv('SQLALCHEMY_URL')
OUTPUT_HTML = os.getenv('OUTPUT_HTML', 'DailyReport.html')
DAY_BANNER = os.getenv('DAY_BANNER')

with open('sections.yml', 'r') as f:
    spec = yaml.safe_load(f)

engine = create_engine(SQLALCHEMY_URL) if SQLALCHEMY_URL else None
sections_payload = []
if engine:
    with engine.connect() as conn:
        for sec in spec.get('sections', []):
            subtables = []
            for st in sec.get('subtables', []):
                df = pd.read_sql(text(st['sql']), conn)
                for t in st.get('transforms', []):
                    if t in TRANSFORMS:
                        df = TRANSFORMS[t](df)
                subtables.append({'title': st['title'], 'columns': list(df.columns), 'rows': df.to_dict(orient='records')})
            sections_payload.append({'name': sec['name'], 'subtables': subtables})
else:
    sections_payload = [{'name': sec['name'], 'subtables': []} for sec in spec.get('sections', [])]

env = Environment(loader=FileSystemLoader('templates'))
tmpl = env.get_template('report.html')
html = tmpl.render(
    generated_at=datetime.now().strftime('%Y-%m-%d %H:%M:%S'),
    meta=spec.get('meta', {}),
    day_banner=DAY_BANNER,
    sections=sections_payload
)
with open(OUTPUT_HTML, 'w') as f:
    f.write(html)
print(f'Wrote {OUTPUT_HTML}')