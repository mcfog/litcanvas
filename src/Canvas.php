<?php namespace Litcanvas;

use Predis\Client;

class Canvas
{
    const REDIS_KEY = 'canvas';
    public static $colorPalette = [
        0 => [255, 255, 255,],#white
        1 => [221, 221, 221,],#silver
        2 => [170, 170, 170,],#gray
        3 => [0, 0, 0,],#black
        4 => [255, 65, 54,],#red
        5 => [255, 133, 27,],#orange
        6 => [255, 220, 0,],#yellow
        7 => [46, 204, 64,],#green
        8 => [129, 219, 255,],#navy
        9 => [0, 116, 217,],#blue
        10 => [117, 13, 201,],#purple
        11 => [247, 131, 172,],#pink
        12 => [1, 255, 112,],#lime
        13 => [57, 204, 204,],#teal
        14 => [101, 6, 79,],#fuchsia
        15 => [160, 106, 66,],#brown
    ];
    /**
     * @var Client
     */
    private $redisClient;

    public function __construct(Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function setPixel(int $x, int $y, int $color)
    {
        if ($color > 15 || $color < 0) {
            throw new \InvalidArgumentException();
        }
        if ($x >= CANVAS_WIDTH || $x < 0) {
            throw new \InvalidArgumentException();
        }
        if ($y >= CANVAS_WIDTH || $y < 0) {
            throw new \InvalidArgumentException();
        }

        $offset = self::cord2offset($x, $y);
        $this->redisClient->bitfield(self::REDIS_KEY, 'SET', 'u4', $offset, $color);
    }

    /**
     * @param int $x
     * @param int $y
     * @return int
     */
    public static function cord2offset(int $x, int $y): int
    {
        return $x + $y * CANVAS_WIDTH;
    }

    public function getImg()
    {
        $img = imagecreate(CANVAS_WIDTH, CANVAS_HEIGHT);

        $color = [];
        $allHex = $this->getAllHex();

        foreach (str_split($allHex) as $i => $c) {
            list($x, $y) = self::offset2cord($i);
            $clr = base_convert($c, 16, 10);

            if (!isset($color[$clr])) {
                $color[$clr] = imagecolorallocate($img,
                    self::$colorPalette[$clr][0],
                    self::$colorPalette[$clr][1],
                    self::$colorPalette[$clr][2]
                );
            }
            imagesetpixel($img, $x, $y, $color[$clr]);
        }

        return $img;
    }

    public function getAllHex()
    {
//        $this->redisClient->set(self::REDIS_KEY, pack('H*', str_repeat('0', 1000 * 1000)));
        $data = $this->redisClient->get(self::REDIS_KEY);

        return current(unpack('H*', $data));
    }

    /**
     * @param int $offset
     * @return int[] $x, $y
     */
    public static function offset2cord(int $offset)
    {
        return [$offset % CANVAS_WIDTH, floor($offset / CANVAS_WIDTH)];
    }

}
