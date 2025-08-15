import pandas as pd

def drop_pii(df: pd.DataFrame) -> pd.DataFrame:
    pii_cols = [c for c in df.columns if any(k in c.lower() for k in ['email','phone','ssn'])]
    return df.drop(columns=pii_cols, errors='ignore')

TRANSFORMS = {
    'drop_pii': drop_pii,
}
