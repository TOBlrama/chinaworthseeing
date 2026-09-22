import fs from 'node:fs/promises';
import path from 'node:path';
import { createRequire } from 'node:module';

const require = createRequire(import.meta.url);
const sharp = require('sharp');

const inputDirectory = process.argv[2];
const outputDirectory = process.argv[3];

if (!inputDirectory || !outputDirectory) {
  throw new Error('Usage: node prepare-home-destination-images.mjs <input-directory> <output-directory>');
}

const cities = [
  ['Beijing.png', 'cws-city-beijing.webp'],
  ['Shanghai.png', 'cws-city-shanghai.webp'],
  ["Xi'an.png", 'cws-city-xian.webp'],
  ['Guilin.png', 'cws-city-guilin.webp'],
  ['Hangzhou.png', 'cws-city-hangzhou.webp'],
  ['Suzhou.png', 'cws-city-suzhou.webp'],
  ['Guangzhou.png', 'cws-city-guangzhou.webp'],
];

await fs.mkdir(outputDirectory, { recursive: true });

for (const [sourceName, outputName] of cities) {
  const sourcePath = path.join(inputDirectory, sourceName);
  const outputPath = path.join(outputDirectory, outputName);
  const metadata = await sharp(sourcePath).metadata();

  if (metadata.width !== 1200 || metadata.height !== 1600) {
    throw new Error(`${sourceName} must be 1200x1600; received ${metadata.width}x${metadata.height}.`);
  }

  const result = await sharp(sourcePath)
    .rotate()
    .webp({ quality: 82, effort: 5, smartSubsample: true })
    .toFile(outputPath);

  process.stdout.write(`${outputName}\t${result.width}x${result.height}\t${result.size} bytes\n`);
}
