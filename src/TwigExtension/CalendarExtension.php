<?php
declare(strict_types=1);

namespace App\TwigExtension;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CalendarExtension extends AbstractExtension
{
    private $twig;

    private $requestStack;

    public function __construct(RequestStack $requestStack, Environment $twig)
    {
        $this->twig = $twig;
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('calendar', [$this, 'renderCalendar'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function renderCalendar(): string
    {
        $request = $this->requestStack->getMasterRequest();

        $now = time();
        $currentDay = (int) date('d', $now);
        if (null !== $request) {
            $now = (int) $request->get('calendar-offset', $now);
        }

        if (abs(time() - $now) >= 2160000) {
            $currentDay = null;
        }

        $firstDayOfMonth = (int) date('w', strtotime('first day of this month', $now));
        $daysCount = (int) date('t', strtotime('first day of this month', $now));

        $calendar = [];
        for ($i = 0; $i < $firstDayOfMonth; ++$i) {
            $calendar[] = null;
        }
        $calendar = array_merge($calendar, range(1, $daysCount));

        return $this->twig->render('admin/_calendar.html.twig', [
            'previousMonth' => (int) strtotime('-1 month', $now),
            'nextMonth' => (int) strtotime('+1 month', $now),
            'today' => abs(time() - $now) >= 2160000 ? time() : null,
            'month' => date('F', $now),
            'year' => date('Y', $now),
            'calendar' => $calendar,
            'currentDay' => $currentDay,
        ]);
    }
}
