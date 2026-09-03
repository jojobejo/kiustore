from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED
import shutil
import tempfile
import xml.etree.ElementTree as ET


BASE_DIR = Path(__file__).resolve().parent
SOURCE = BASE_DIR / "GUIDEBOOK_ADMIN_CUSTOMER_20260821.docx"
OUTPUT = BASE_DIR / "GUIDEBOOK_ADMIN_CUSTOMER_20260821_WORD2010.docx"

NS = {"w": "http://schemas.openxmlformats.org/wordprocessingml/2006/main"}
ET.register_namespace("w", NS["w"])


def ensure_word2010_compatibility(settings_path: Path):
    tree = ET.parse(settings_path)
    root = tree.getroot()
    compat = root.find("w:compat", NS)
    if compat is None:
        compat = ET.SubElement(root, f"{{{NS['w']}}}compat")

    compat_setting = None
    for setting in compat.findall("w:compatSetting", NS):
        if setting.get(f"{{{NS['w']}}}name") == "compatibilityMode":
            compat_setting = setting
            break

    if compat_setting is None:
        compat_setting = ET.SubElement(compat, f"{{{NS['w']}}}compatSetting")

    compat_setting.set(f"{{{NS['w']}}}name", "compatibilityMode")
    compat_setting.set(f"{{{NS['w']}}}uri", "http://schemas.microsoft.com/office/word")
    compat_setting.set(f"{{{NS['w']}}}val", "14")
    tree.write(settings_path, encoding="UTF-8", xml_declaration=True)


def build_word2010_docx():
    if not SOURCE.exists():
        raise FileNotFoundError(f"Source DOCX not found: {SOURCE}")

    with tempfile.TemporaryDirectory() as tmp:
        tmp_dir = Path(tmp)
        with ZipFile(SOURCE, "r") as zin:
            zin.extractall(tmp_dir)

        ensure_word2010_compatibility(tmp_dir / "word" / "settings.xml")

        if OUTPUT.exists():
            OUTPUT.unlink()
        with ZipFile(OUTPUT, "w", ZIP_DEFLATED) as zout:
            for file_path in tmp_dir.rglob("*"):
                if file_path.is_file():
                    zout.write(file_path, file_path.relative_to(tmp_dir).as_posix())


if __name__ == "__main__":
    build_word2010_docx()
    print(OUTPUT)
